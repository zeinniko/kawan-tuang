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
     * Generate URL untuk menampilkan/mengunduh file.
     * 
     * @param string|null $path Path file yang tersimpan di DB
     * @param string $visibility Options: 'public' | 'private'
     * @param int $ttlMinutes Masa berlaku URL jika private (default 60 menit)
     * @return string|null Absolute URL file
     */
    public function getUrl(?string $path, string $visibility = 'public', int $ttlMinutes = 60): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Jika path sudah berupa URL eksternal, kembalikan langsung
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $disk = $this->getDisk($visibility);

        if ($visibility === 'private') {
            if ($disk === 's3') {
                return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($ttlMinutes));
            }

            // Fallback untuk file private lokal via route terproteksi
            return route('private.file.download', ['path' => encrypt($path)]);
        }

        return Storage::disk($disk)->url($path);
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