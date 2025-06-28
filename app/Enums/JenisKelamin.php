<?php

namespace App\Enums;

enum JenisKelamin: string
{
    case LAKI_LAKI = 'L';
    case PEREMPUAN = 'P';

    public function label(): string
    {
        return match($this) {
            self::LAKI_LAKI => 'Laki-laki',
            self::PEREMPUAN => 'Perempuan',
        };
    }

    public static function options(): array
    {
        return [
            self::LAKI_LAKI->value => self::LAKI_LAKI->label(),
            self::PEREMPUAN->value => self::PEREMPUAN->label(),
        ];
    }
}