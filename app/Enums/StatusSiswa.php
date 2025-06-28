<?php

namespace App\Enums;

enum StatusSiswa: string
{
    case AKTIF = 'aktif';
    case NONAKTIF = 'nonaktif';
    case ALUMNI = 'alumni';

    public function label(): string
    {
        return match($this) {
            self::AKTIF => 'Aktif',
            self::NONAKTIF => 'Nonaktif',
            self::ALUMNI => 'Alumni',
        };
    }

    public static function options(): array
    {
        return [
            self::AKTIF->value => self::AKTIF->label(),
            self::NONAKTIF->value => self::NONAKTIF->label(),
            self::ALUMNI->value => self::ALUMNI->label(),
        ];
    }
}
