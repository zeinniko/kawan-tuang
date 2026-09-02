<?php

namespace App\Services;

use App\Jobs\CreateBiteshipOrderJob;
use App\Models\Order;
use App\Models\Payment; // Import Model Payment
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransPaymentService
{
    protected string $serverKey;
    protected bool $isProduction;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key', '');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function createSnapToken(Order $order): array
    {
        $grossAmount = (int) round($order->total_amount);

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->user->full_name ?? $order->user->name ?? 'Customer',
                'email'      => $order->user->email ?? '',
                'phone'      => $order->user->phone_number ?? '',
            ],
            'callbacks' => [
                'finish' => config('app.url', 'http://localhost:8000') . '/orders/' . $order->id,
            ],
        ];

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Accept' => 'application/json', 'Content-Type' => 'application/json'])
            ->post($this->snapUrl, $params);

        if ($response->failed()) {
            throw new Exception('Gagal membuat transaksi di Midtrans: ' . $response->body());
        }

        $responseData = $response->json();

        return [
            'order_id'     => $order->id,
            'snap_token'   => $responseData['token'] ?? null,
            'redirect_url' => $responseData['redirect_url'] ?? null,
        ];
    }

    public function handleNotification(array $notification): void
    {
        $orderNumber       = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus       = $notification['fraud_status'] ?? null;
        $signatureKey      = $notification['signature_key'] ?? null;
        $statusCode        = $notification['status_code'] ?? null;
        $grossAmount       = $notification['gross_amount'] ?? null;

        if (!$orderNumber) {
            throw new Exception('Order ID tidak ditemukan dalam payload notification.');
        }

        // 1. Verifikasi Signature Key Midtrans
        $expectedSignature = hash('sha512', $orderNumber . $statusCode . $grossAmount . $this->serverKey);
        if ($signatureKey !== $expectedSignature) {
            Log::error('[MIDTRANS SIGNATURE MISMATCH]', [
                'received' => $signatureKey,
                'expected' => $expectedSignature
            ]);
            throw new Exception('Integritas Signature Key Midtrans tidak valid.');
        }

        // 2. Cari Order di Database
        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            throw new Exception("Order dengan nomor {$orderNumber} tidak ditemukan.");
        }

        $isPaid = false;
        $paymentStatus = 'pending';

        // 3. Tentukan Status Pembayaran
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $isPaid = true;
                $paymentStatus = 'settlement';
            }
        } elseif ($transactionStatus === 'settlement') {
            $isPaid = true;
            $paymentStatus = 'settlement';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $paymentStatus = $transactionStatus;
            $order->update(['status' => Order::STATUS_CANCELLED]);
        } elseif ($transactionStatus === 'pending') {
            $paymentStatus = 'pending';
            $order->update(['status' => Order::STATUS_PENDING_PAYMENT]);
        }

        // 4. BIKIN / UPDATE RECORD DI TABEL PAYMENTS (Perbaikan Utama)
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method'         => $notification['payment_type'] ?? 'midtrans',
                'gateway_transaction_id' => $notification['transaction_id'] ?? null,
                'payment_status'         => $paymentStatus,
                'paid_at'                => $isPaid ? now() : null,
                'raw_response'           => $notification,
            ]
        );

        // 5. JIKA SUDAH LUNAS: Update Order & Trigger Biteship
        if ($isPaid) {
            // Mencegah eksekusi ganda jika sudah berstatus PAID
            if ($order->status !== Order::STATUS_PAID) {
                $order->update(['status' => Order::STATUS_PAID]);

                Log::info("[ORDER PAID] Order {$order->order_number} berhasil dibayar.");

                // Panggil Biteship HANYA JIKA tipe pengiriman adalah DELIVERY
                if ($order->fulfillment_type === 'delivery') {
                    Log::info("[BITESHIP DISPATCH] Memulai Job Biteship untuk order {$order->order_number}");
                    CreateBiteshipOrderJob::dispatch($order);
                }
            }
        }
    }
}