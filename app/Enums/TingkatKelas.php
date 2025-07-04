<?php

namespace App\Enums;

enum TingkatKelas: string
{
    case X = '10';
    case XI = '11';
    case XII = '12';

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
