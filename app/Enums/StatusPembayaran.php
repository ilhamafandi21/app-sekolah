<?php

namespace App\Enums;

enum StatusPembayaran: string
{
    case LUNAS = 'lunas';
    case PENDING = 'pending';

    public function label(): string
    {
        return match($this) {
            self::LUNAS => 'Lunas',
            self::PENDING => 'Pending',
        };
    }

    public static function options(): array
    {
        return [
            self::LUNAS->value => self::LUNAS->label(),
            self::PENDING->value => self::PENDING->label(),
        ];
    }
}
