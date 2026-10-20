<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = database_path('seeders/products.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: {$csvFile}");
            $this->command->info("Letakkan file CSV Anda di: database/seeders/products.csv");
            return;
        }

        $file = fopen($csvFile, 'r');
        
        // Lewati baris header
        fgetcsv($file, 1000, ',');

        $count = 0;
        $skipped = 0;

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            // Urutan Kolom CSV:
            // [0] NO | [1] NAMA ITEM | [2] HARGA TOKO | [3] GAMBAR | [4] CATEGORY | [5] BRAND | [6] ABV | [7] VOLUME | [8] BENTUK
            
            $namaItem     = $this->sanitizeUtf8($row[1] ?? '');
            $hargaToko    = (float) ($row[2] ?? 0);
            $gambarName   = $this->sanitizeUtf8($row[3] ?? '');
            $categoryName = $this->sanitizeUtf8($row[4] ?? 'Uncategorized');
            $brandName    = $this->sanitizeUtf8($row[5] ?? 'Generic');
            $rawAbv       = $row[6] ?? '0';
            $rawVolume    = $row[7] ?? '0';
            $bentuk       = $this->sanitizeUtf8($row[8] ?? 'Botol');

            if (empty($namaItem)) {
                continue;
            }

            // 1. Cek Slug untuk Mencegah Duplikasi Produk
            $slug = Str::slug($namaItem);

            if (Product::where('slug', $slug)->exists()) {
                $skipped++;
                continue;
            }

            // 2. Buat / Cari Category & Brand
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName)]
            );

            $brand = Brand::firstOrCreate(
                ['name' => $brandName],
                ['slug' => Str::slug($brandName)]
            );

            // 3. Parse ABV & Volume
            $abv      = $this->parseAbv($rawAbv);
            $volumeMl = $this->parseVolume($rawVolume);

            // 4. Susun Deskripsi Kalimat Otomatis
            $description = "{$namaItem} merupakan produk {$categoryName} dari {$brandName}. Minuman ini dikemas dalam bentuk {$bentuk} berukuran {$volumeMl}ml dengan kadar alkohol (ABV) sebesar {$abv}%.";

            // 5. SKU Unik dengan Nomor Urut
            $categoryPrefix = strtoupper(substr(Str::slug($categoryName), 0, 3));
            $namePrefix     = strtoupper(substr(Str::slug($namaItem), 0, 10));
            $sequence       = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            $sku            = "{$categoryPrefix}-{$namePrefix}-{$volumeMl}-{$sequence}";

            // 6. Simpan Product (Stock = 0)
            $product = Product::create([
                'category_id'   => $category->id,
                'brand_id'      => $brand->id,
                'name'          => $namaItem,
                'slug'          => $slug,
                'description'   => $description,
                'abv'           => $abv,
                'volume_ml'     => $volumeMl,
                'price'         => $hargaToko,
                'strike_price'  => null,
                'stock'         => 0,
                'is_cold_ready' => true,
                'is_active'     => true,
                'sku'           => $sku,
            ]);

            // 7. Simpan & Renama Gambar ke Storage Local Public
            if (!empty($gambarName)) {
                $cleanImageName = html_entity_decode($gambarName, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                // Cari file lokal sumber berdasarkan nama asli CSV (Case-insensitive)
                $sourcePath = $this->findLocalFile(public_path('images/TIPSY MORE'), $cleanImageName);

                if ($sourcePath && File::exists($sourcePath)) {
                    // Ambil ekstensi asli (misal: png, jpg)
                    $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

                    // Generate nama file baru murni tanpa menggunakan nama file asli
                    // Contoh hasil: 1800-guachimonton-a1b2c3.png
                    $newFilename = $product->slug . '-' . Str::random(6) . '.' . $extension;

                    // Path simpan di storage/app/public/products/
                    $relativePath = 'products/' . $newFilename;

                    // Simpan ke disk 'public' dengan nama file baru
                    Storage::disk('public')->put(
                        $relativePath,
                        File::get($sourcePath)
                    );

                    // Simpan relatif path baru ke DB
                    ProductImage::create([
                        'product_id'    => $product->id,
                        'image_url'     => $relativePath,
                        'is_primary'    => true,
                        'display_order' => 1,
                    ]);
                } else {
                    $this->command->warn("Gambar lokal tidak ditemukan: {$cleanImageName}");
                }
            }

            $count++;
        }

        fclose($file);

        $this->command->info("Berhasil mengimpor {$count} produk dengan nama file gambar terenkripsi/baru!");
        if ($skipped > 0) {
            $this->command->warn("Melewati {$skipped} produk duplikat yang sudah ada di database.");
        }
    }

    /**
     * Pencarian file lokal secara case-insensitive.
     */
    private function findLocalFile(string $folderPath, string $filename): ?string
    {
        $directPath = $folderPath . '/' . $filename;
        if (File::exists($directPath)) {
            return $directPath;
        }

        if (!File::isDirectory($folderPath)) {
            return null;
        }

        foreach (File::files($folderPath) as $file) {
            if (strcasecmp($file->getFilename(), $filename) === 0) {
                return $file->getPathname();
            }
        }

        return null;
    }

    private function sanitizeUtf8(?string $text): string
    {
        if (!$text) return '';
        $cleaned = mb_convert_encoding(trim($text), 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
        return iconv('UTF-8', 'UTF-8//IGNORE', $cleaned);
    }

    private function parseAbv(mixed $rawAbv): float
    {
        if (is_null($rawAbv) || $rawAbv === '') {
            return 0.0;
        }

        preg_match_all('/(?:\d+\.\d+|\d+)/', (string) $rawAbv, $matches);

        if (empty($matches[0])) {
            return 0.0;
        }

        $numbers = array_map('floatval', $matches[0]);
        $value = array_sum($numbers) / count($numbers);

        if ($value > 0 && $value <= 1.0 && count($numbers) === 1) {
            $value *= 100;
        }

        return round($value, 1);
    }

    private function parseVolume(mixed $rawVolume): int
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $rawVolume);
        return $clean !== '' ? (int) $clean : 0;
    }
}