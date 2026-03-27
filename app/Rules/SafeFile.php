<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Validasi file berdasarkan magic bytes (file signature) — bukan hanya ekstensi.
 * Mencegah upload file berbahaya yang di-rename (misal: shell.php → shell.jpg).
 */
class SafeFile implements ValidationRule
{
    /**
     * Map ekstensi yang diizinkan beserta magic bytes-nya.
     * Format: 'ekstensi' => [offset_byte, [hex_signatures...]]
     */
    private const ALLOWED_SIGNATURES = [
        // JPEG: FF D8 FF
        'jpg'  => [0, ['ffd8ff']],
        'jpeg' => [0, ['ffd8ff']],

        // PNG: 89 50 4E 47 0D 0A 1A 0A
        'png'  => [0, ['89504e47']],

        // PDF: 25 50 44 46 (%PDF)
        'pdf'  => [0, ['25504446']],
    ];

    /**
     * Signature berbahaya yang SELALU ditolak (apapun ekstensinya).
     * Offset => [signatures]
     */
    private const DANGEROUS_SIGNATURES = [
        [0, ['3c3f706870',    // <?php
               '4d5a',         // MZ (Windows executable)
               '7f454c46',     // ELF (Linux executable)
               '504b0304',]],  // ZIP/JAR (bisa berisi exploit)
    ];

    /**
     * Jalankan validasi.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! ($value instanceof UploadedFile)) {
            $fail('File tidak valid.');
            return;
        }

        $path = $value->getRealPath();

        if (! $path || ! file_exists($path)) {
            $fail('File tidak dapat dibaca.');
            return;
        }

        // Baca 8 byte pertama file
        $handle = fopen($path, 'rb');
        if (! $handle) {
            $fail('File tidak dapat dibuka untuk diverifikasi.');
            return;
        }

        $header = fread($handle, 8);
        fclose($handle);

        $hexHeader = strtolower(bin2hex($header));

        // 1. Cek signature berbahaya terlebih dahulu
        foreach (self::DANGEROUS_SIGNATURES as [$offset, $signatures]) {
            foreach ($signatures as $sig) {
                $sigLen = strlen($sig);
                $slice  = substr($hexHeader, $offset * 2, $sigLen);
                if ($slice === $sig) {
                    $fail('File yang diunggah teridentifikasi sebagai file berbahaya dan tidak diizinkan.');
                    return;
                }
            }
        }

        // 2. Cek ekstensi file
        $extension = strtolower($value->getClientOriginalExtension());

        if (! isset(self::ALLOWED_SIGNATURES[$extension])) {
            $fail("Tipe file .{$extension} tidak diizinkan. Hanya PDF, JPG, dan PNG yang diterima.");
            return;
        }

        // 3. Cocokkan magic bytes dengan ekstensi yang diklaim
        [$offset, $signatures] = self::ALLOWED_SIGNATURES[$extension];
        $matched = false;

        foreach ($signatures as $sig) {
            $sigLen = strlen($sig);
            $slice  = substr($hexHeader, $offset * 2, $sigLen);
            if ($slice === $sig) {
                $matched = true;
                break;
            }
        }

        if (! $matched) {
            $fail("Konten file tidak sesuai dengan ekstensi .{$extension}. File mungkin telah diubah atau berbahaya.");
        }
    }
}
