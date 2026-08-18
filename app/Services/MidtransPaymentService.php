<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Http;

class MidtransPaymentService
{
    protected string $serverKey;
    protected bool $isProduction;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->isProduction = config('services.midtrans.is_production', false);
        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function createSnapToken(Order $order): array
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) round($order->grand_total),
            ],
            'customer_details' => [
                'first_name' => $order->user->full_name,
                'email' => $order->user->email,
                'phone' => $order->user->phone_number,
            ],
            'callbacks' => [
                'finish' => config('app.frontend_url', 'http://localhost:3000') . '/orders/' . $order->id,
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
            'order_id' => $order->id,
            'snap_token' => $responseData['token'],
            'redirect_url' => $responseData['redirect_url'],
        ];
    }

    public function handleNotification(array $notification): void
    {
        $orderNumber = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;
        $signatureKey = $notification['signature_key'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;

        if (! $orderNumber) {
            return;
        }

        // Verifikasi Signature Key untuk memastikan integritas data dari Midtrans
        $expectedSignature = hash('sha512', $orderNumber . $statusCode . $grossAmount . $this->serverKey);
        if ($signatureKey !== $expectedSignature) {
            throw new Exception('Integritas Signature Key Midtrans tidak valid.');
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (! $order) {
            return;
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        } elseif ($transactionStatus === 'pending') {
            $order->update(['payment_status' => 'unpaid']);
        }
    }
}