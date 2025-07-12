<?php

namespace App\Enums;

enum StatusEnum: int
{
    case AKTIF = 1;
    case NONAKTIF = 0;

    public function label(): string
    {
        return match($this) {
            self::AKTIF => 'Aktif',
            self::NONAKTIF => 'Nonaktif',
        };
    }

    public static function options(): array
    {
        return [
            self::AKTIF->value => self::AKTIF->label(),
            self::NONAKTIF->value => self::NONAKTIF->label(),
        ];
    }
}
