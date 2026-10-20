<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    /**
     * Memeriksa keberadaan kredensial AWS S3.
     */
    public function isAwsConfigured(): bool
    {
        return !empty(config('filesystems.disks.s3.key'))
            && !empty(config('filesystems.disks.s3.secret'))
            && !empty(config('filesystems.disks.s3.bucket'))
            && !empty(config('filesystems.disks.s3.region'));
    }

    /**
     * Menentukan disk target berdasarkan konfigurasi AWS dan akses visibilitas.
     */
    public function getDisk(string $visibility = 'public'): string
    {
        if ($this->isAwsConfigured()) {
            return 's3';
        }

        return $visibility === 'public' ? 'public' : 'local';
    }

    /**
     * Upload file ke storage (Public atau Private).
     * 
     * @param UploadedFile $file File yang diunggah dari request
     * @param string $directory Subfolder tujuan (contoh: 'brands', 'invoices')
     * @param string $visibility Options: 'public' | 'private'
     * @param string|null $customFilename Nama file kustom (opsional)
     * @return string Relative path file yang tersimpan
     */
    public function upload(
        UploadedFile $file,
        string $directory = 'uploads',
        string $visibility = 'public',
        ?string $customFilename = null
    ): string {
        $disk = $this->getDisk($visibility);
        $extension = $file->getClientOriginalExtension();
        
        $filename = $customFilename 
            ? Str::slug(pathinfo($customFilename, PATHINFO_FILENAME)) . '.' . $extension 
            : Str::uuid() . '.' . $extension;

        return $file->storeAs($directory, $filename, [
            'disk'       => $disk,
            'visibility' => $visibility,
        ]);
    }

    /**
     * Generate URL file secara bertingkat:
     * 1. Cek keberadaan di AWS S3
     * 2. Cek keberadaan di Storage Local Public
     * 3. Fallback ke Gambar Default / Placeholder
     * 
     * @param string|null $path Path file yang tersimpan di DB
     * @param string $visibility Options: 'public' | 'private'
     * @param int $ttlMinutes Masa berlaku URL jika private (default 60 menit)
     * @param string|null $defaultPath URL/Path alternatif jika file tidak ditemukan
     * @return string Absolute URL file
     */
    public function getUrl(
        ?string $path,
        string $visibility = 'public',
        int $ttlMinutes = 60,
        ?string $defaultPath = null
    ): string {
        $defaultImage = $defaultPath ?? asset('images/placeholder-product.png');

        if (empty($path)) {
            return $defaultImage;
        }

        // Jika path sudah berupa URL eksternal, kembalikan langsung
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Penanganan Khusus Storage Private
        if ($visibility === 'private') {
            if ($this->isAwsConfigured()) {
                try {
                    if (Storage::disk('s3')->exists($path)) {
                        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($ttlMinutes));
                    }
                } catch (\Throwable $e) {
                    // Bypass ke lokal jika koneksi/kredensial S3 bermasalah
                }
            }

            if (Storage::disk('local')->exists($path)) {
                return route('private.file.download', ['path' => encrypt($path)]);
            }

            return $defaultImage;
        }

        // --- SKEMA BERTINGKAT UNTUK STORAGE PUBLIC ---

        // 1. Cek AWS S3 jika terkonfigurasi
        if ($this->isAwsConfigured()) {
            try {
                if (Storage::disk('s3')->exists($path)) {
                    return Storage::disk('s3')->url($path);
                }
            } catch (\Throwable $e) {
                // Bypass ke lokal jika S3 mengalami timeout/error
            }
        }

        // 2. Cek Storage Local Public (storage/app/public)
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        // 3. Fallback Gambar Default
        return $defaultImage;
    }

    /**
     * Menghapus file dari storage.
     */
    public function delete(?string $path, string $visibility = 'public'): bool
    {
        if (empty($path) || str_starts_with($path, 'http')) {
            return false;
        }

        $disk = $this->getDisk($visibility);

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}