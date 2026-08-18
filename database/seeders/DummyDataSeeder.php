<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Store;
use App\Models\StoreStock;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // ----------------------------------------------------
            // 1. DUMMY KATEGORI & BRAND
            // ----------------------------------------------------
            $categoryWine = Category::firstOrCreate(['name' => 'Wine & Champagne'], [
                'slug' => 'wine-champagne',
            ]);

            $categoryBeer = Category::firstOrCreate(['name' => 'Beer & Cider'], [
                'slug' => 'beer-cider',
            ]);

            $brandSababay = Brand::firstOrCreate(['name' => 'Sababay Winery']);

            $brandBintang = Brand::firstOrCreate(['name' => 'Multi Bintang']);

            // ----------------------------------------------------
            // 2. DUMMY STORES (CABANG TOKO)
            // ----------------------------------------------------
            $storeJkt = Store::create([
                'store_code'       => 'TT-JKT01',
                'name'             => 'Teman Tuang - Kemang',
                'address'          => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'latitude'         => '-6.260823',
                'longitude'        => '106.814343',
                'phone_number'     => '081299887766',
                'open_time'        => '09:00:00',
                'close_time'       => '23:00:00',
                'is_pickup_active' => true,
                'is_active'        => true,
            ]);

            $storeBdg = Store::create([
                'store_code'       => 'TT-BDG01',
                'name'             => 'Teman Tuang - Dago',
                'address'          => 'Jl. Ir. H. Juanda No. 120, Bandung',
                'latitude'         => '-6.885123',
                'longitude'        => '107.613456',
                'phone_number'     => '081388776655',
                'open_time'        => '10:00:00',
                'close_time'       => '22:00:00',
                'is_pickup_active' => true,
                'is_active'        => true,
            ]);

            // ----------------------------------------------------
            // 3. DUMMY PRODUK & STOK TOKO
            // ----------------------------------------------------
            $product1 = Product::create([
                'category_id'   => $categoryWine->id,
                'brand_id'      => $brandSababay->id,
                'name'          => 'Sababay Reserve Red Wine 750ml',
                'slug'          => 'sababay-reserve-red-wine-750ml',
                'description'   => 'Red wine lokal berkualitas tinggi dengan cita rasa berry manis segar.',
                'abv'           => 13.5,
                'volume_ml'     => 750,
                'price'         => 350000,
                'strike_price'  => 380000,
                'stock'         => 100,
                'is_cold_ready' => true,
                'is_active'      => true,
                'sku'           => 'TT-WINE-001',
            ]);

            $product2 = Product::create([
                'category_id'   => $categoryBeer->id,
                'brand_id'      => $brandBintang->id,
                'name'          => 'Bintang Pilsener Can 500ml',
                'slug'          => 'bintang-pilsener-can-500ml',
                'description'   => 'Bir pilsener favorit Indonesia, disajikan dingin menyegarkan.',
                'abv'           => 4.7,
                'volume_ml'     => 500,
                'price'         => 40000,
                'strike_price'  => 45000,
                'stock'         => 200,
                'is_cold_ready' => true,
                'is_active'      => true,
                'sku'           => 'TT-BEER-001',
            ]);

            foreach ([$storeJkt, $storeBdg] as $store) {
                StoreStock::create([
                    'store_id'   => $store->id,
                    'product_id' => $product1->id,
                    'stock'      => 50,
                    'cold_stock' => 20,
                ]);

                StoreStock::create([
                    'store_id'   => $store->id,
                    'product_id' => $product2->id,
                    'stock'      => 100,
                    'cold_stock' => 50,
                ]);
            }

            // ----------------------------------------------------
            // 4. DUMMY USERS & ALAMAT
            // ----------------------------------------------------
            $admin = User::create([
                'full_name'       => 'Administrator Utama',
                'email'           => 'admin@temantuang.id',
                'phone_number'    => '081111111111',
                'password'        => Hash::make('password'),
                'birth_date'      => '1990-01-01',
                'is_age_verified' => true,
                'role'            => 'admin',
            ]);

            $user1 = User::create([
                'full_name'       => 'Budi Santoso',
                'email'           => 'budi@example.com',
                'phone_number'    => '081234567890',
                'password'        => Hash::make('password'),
                'birth_date'      => '1998-05-14',
                'is_age_verified' => true,
                'role'            => 'customer',
            ]);

            $user2 = User::create([
                'full_name'       => 'Siti Rahma',
                'email'           => 'siti@example.com',
                'phone_number'    => '089876543210',
                'password'        => Hash::make('password'),
                'birth_date'      => '2001-08-22',
                'is_age_verified' => true,
                'role'            => 'customer',
            ]);

            $address1 = UserAddress::create([
                'user_id'         => $user1->id,
                'label'           => 'Rumah',
                'recipient_name'  => 'Budi Santoso',
                'recipient_phone' => '081234567890',
                'full_address'    => 'Jl. Ampera Raya No. 45, Cilandak, Jakarta Selatan',
                'latitude'        => '-6.280123',
                'longitude'       => '106.819456',
                'is_primary'      => true,
            ]);

            $address2 = UserAddress::create([
                'user_id'         => $user2->id,
                'label'           => 'Apartemen',
                'recipient_name'  => 'Siti Rahma',
                'recipient_phone' => '089876543210',
                'full_address'    => 'Tower B Lt. 12, Apartemen Dago, Bandung',
                'latitude'        => '-6.889012',
                'longitude'       => '107.615678',
                'is_primary'      => true,
            ]);

            // ----------------------------------------------------
            // 5. DUMMY VOUCHER
            // ----------------------------------------------------
            $voucherDisc = Voucher::create([
                'code'                => 'HEBAT50K',
                'discount_type'       => 'fixed_amount',
                'discount_value'      => 50000,
                'min_order_amount'    => 200000,
                'max_discount_amount' => 50000,
                'valid_from'          => now()->subDays(10),
                'valid_until'         => now()->addDays(30),
                'usage_limit'         => 100,
            ]);

            $voucherShip = Voucher::create([
                'code'                => 'FREESHIP15K',
                'discount_type'       => 'fixed_amount',
                'discount_value'      => 15000,
                'min_order_amount'    => 100000,
                'max_discount_amount' => 15000,
                'valid_from'          => now()->subDays(10),
                'valid_until'         => now()->addDays(30),
                'usage_limit'         => 100,
            ]);

            // ====================================================
            // SKEMA 1: STORE PICKUP + VOUCHER + COMPLETED + REVIEW
            // ====================================================
            $order1 = Order::create([
                'order_number'     => 'TT-ORD-001',
                'user_id'          => $user1->id,
                'store_id'         => $storeJkt->id,
                'voucher_id'       => $voucherDisc->id,
                'fulfillment_type' => 'pickup',
                'pickup_code'      => '88921',
                'pickup_qr_url'    => 'https://api.qrserver.com/v1/create-qr-code/?data=88921',
                'subtotal'         => 390000,
                'discount_amount'  => 50000,
                'delivery_fee'     => 0,
                'admin_fee'        => 2000,
                'total_amount'     => 342000,
                'status'           => 'completed',
                'address_snapshot' => [
                    'store_name' => $storeJkt->name,
                    'address'    => $storeJkt->address,
                ],
                'created_at'       => now()->subDays(2),
            ]);

            OrderItem::create([
                'order_id'              => $order1->id,
                'product_id'            => $product1->id,
                'product_name_snapshot' => $product1->name,
                'unit_price'            => $product1->price,
                'quantity'              => 1,
                'subtotal_price'        => 350000,
            ]);

            OrderItem::create([
                'order_id'              => $order1->id,
                'product_id'            => $product2->id,
                'product_name_snapshot' => $product2->name,
                'unit_price'            => $product2->price,
                'quantity'              => 1,
                'subtotal_price'        => 40000,
            ]);

            Payment::create([
                'order_id'               => $order1->id,
                'payment_method'         => 'qris',
                'gateway_transaction_id' => 'TRX-QRIS-998811',
                'payment_status'         => 'paid',
                'paid_at'                => now()->subDays(2),
                'raw_response'           => ['status' => 'settlement', 'issuer' => 'Gopay'],
            ]);

            ProductReview::create([
                'user_id'     => $user1->id,
                'product_id'  => $product1->id,
                'order_id'    => $order1->id,
                'rating'      => 5,
                'review_text' => 'Pickup sangat praktis lewat QR code, stok dingin terjaga dengan baik!',
                'photo_url'   => 'reviews/sample-wine.jpg',
            ]);

            // ====================================================
            // SKEMA 2: DELIVERY DALAM KOTA (INSTANT) + TANPA VOUCHER + DELIVERING
            // ====================================================
            $order2 = Order::create([
                'order_number'     => 'TT-ORD-002',
                'user_id'          => $user1->id,
                'store_id'         => $storeJkt->id,
                'voucher_id'       => null,
                'fulfillment_type' => 'delivery',
                'subtotal'         => 350000,
                'discount_amount'  => 0,
                'delivery_fee'     => 20000,
                'admin_fee'        => 2000,
                'total_amount'     => 372000,
                'status'           => 'delivering',
                'address_snapshot' => [
                    'recipient_name'  => $address1->recipient_name,
                    'recipient_phone' => $address1->recipient_phone,
                    'full_address'    => $address1->full_address,
                ],
                'created_at'       => now()->subHours(3),
            ]);

            OrderItem::create([
                'order_id'              => $order2->id,
                'product_id'            => $product1->id,
                'product_name_snapshot' => $product1->name,
                'unit_price'            => $product1->price,
                'quantity'              => 1,
                'subtotal_price'        => 350000,
            ]);

            Payment::create([
                'order_id'               => $order2->id,
                'payment_method'         => 'gopay',
                'gateway_transaction_id' => 'TRX-GOPAY-554433',
                'payment_status'         => 'paid',
                'paid_at'                => now()->subHours(3),
            ]);

            DB::table('deliveries')->insert([
                'id'                => (string) Str::uuid(),
                'order_id'          => $order2->id,
                'courier_provider'  => 'Gojek Instant',
                'service_type'      => 'Instant Delivery',
                'waybill_number'    => 'GK-88991122',
                'driver_name'       => 'Ahmad Driver',
                'driver_phone'      => '085544332211',
                'live_tracking_url' => 'https://track.gojek.com/sample',
                'status'            => 'on_the_way',
                'created_at'        => now()->subHours(2),
                'updated_at'        => now()->subHours(2),
            ]);

            // ====================================================
            // SKEMA 3: SHIPPING LUAR KOTA (EKSPEDISI) + VOUCHER + PROCESSING
            // ====================================================
            $order3 = Order::create([
                'order_number'     => 'TT-ORD-003',
                'user_id'          => $user2->id,
                'store_id'         => $storeBdg->id,
                'voucher_id'       => $voucherShip->id,
                'fulfillment_type' => 'delivery',
                'subtotal'         => 200000,
                'discount_amount'  => 15000,
                'delivery_fee'     => 35000,
                'admin_fee'        => 2000,
                'total_amount'     => 222000,
                'status'           => 'processing',
                'address_snapshot' => [
                    'recipient_name'  => $address2->recipient_name,
                    'recipient_phone' => $address2->recipient_phone,
                    'full_address'    => $address2->full_address,
                ],
                'created_at'       => now()->subHours(5),
            ]);

            OrderItem::create([
                'order_id'              => $order3->id,
                'product_id'            => $product2->id,
                'product_name_snapshot' => $product2->name,
                'unit_price'            => $product2->price,
                'quantity'              => 5,
                'subtotal_price'        => 200000,
            ]);

            Payment::create([
                'order_id'               => $order3->id,
                'payment_method'         => 'bank_transfer',
                'gateway_transaction_id' => 'TRX-BCA-112233',
                'payment_status'         => 'paid',
                'paid_at'                => now()->subHours(4),
            ]);

            DB::table('deliveries')->insert([
                'id'               => (string) Str::uuid(),
                'order_id'         => $order3->id,
                'courier_provider' => 'JNE Express',
                'service_type'     => 'JNE YES (Yakin Esok Sampai)',
                'waybill_number'   => 'JNE9988776655',
                'status'           => 'booking',
                'created_at'       => now()->subHours(4),
                'updated_at'       => now()->subHours(4),
            ]);

            // ====================================================
            // SKEMA 4: ORDER CANCELLED (PENDING PAYMENT EXPIRED)
            // ====================================================
            $order4 = Order::create([
                'order_number'     => 'TT-ORD-004',
                'user_id'          => $user2->id,
                'store_id'         => $storeBdg->id,
                'voucher_id'       => null,
                'fulfillment_type' => 'delivery',
                'subtotal'         => 80000,
                'discount_amount'  => 0,
                'delivery_fee'     => 15000,
                'admin_fee'        => 2000,
                'total_amount'     => 97000,
                'status'           => 'cancelled',
                'address_snapshot' => [
                    'recipient_name'  => $address2->recipient_name,
                    'recipient_phone' => $address2->recipient_phone,
                    'full_address'    => $address2->full_address,
                ],
                'created_at'       => now()->subDays(3),
            ]);

            OrderItem::create([
                'order_id'              => $order4->id,
                'product_id'            => $product2->id,
                'product_name_snapshot' => $product2->name,
                'unit_price'            => $product2->price,
                'quantity'              => 2,
                'subtotal_price'        => 80000,
            ]);

            Payment::create([
                'order_id'               => $order4->id,
                'payment_method'         => 'virtual_account',
                'gateway_transaction_id' => 'TRX-VA-EXP-1122',
                'payment_status'         => 'expired',
            ]);
        });
    }
}