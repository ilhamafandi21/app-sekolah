<?php

namespace App\Traits;

trait GeneratesKodeFromName
{
    public function generateKodeFromName(string $name): string
    {
        // Hapus karakter selain huruf/angka/spasi
        $name = strtoupper(preg_replace('/[^A-Z0-9 ]/i', '', $name));
        $words = preg_split('/\s+/', trim($name));

        $kode = '';

        if (count($words) === 1) {
            $kode = substr($words[0], 0, 4);
        } elseif (count($words) === 2) {
            $kode = substr($words[0], 0, 2) . substr($words[1], 0, 3);
        } else {
            $kode .= substr($words[0], 0, 2);
            foreach (array_slice($words, 1) as $word) {
                $kode .= substr($word, 0, 1);
            }
        }

        return strtoupper(substr($kode, 0, 8));
    }
}
