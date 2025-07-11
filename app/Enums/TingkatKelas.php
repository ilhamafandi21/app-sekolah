<?php

namespace App\Enums;

enum TingkatKelas: string
{
    case X = 'X';
    case XI = 'XI';
    case XII = 'XII';

    public function label(): string
    {
        return match($this) {
            self::X => 'SEPULUH',
            self::XI => 'SEBELAS',
            self::XII => 'DUABELAS',
        };
    }

    public static function options(): array
    {
        return [
            self::X->value => self::X->label(),
            self::XI->value => self::XI->label(),
            self::XII->value => self::XII->label(),
        ];
    }
}
