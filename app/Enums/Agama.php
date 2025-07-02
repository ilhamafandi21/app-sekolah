<?php

namespace App\Enums;

enum Agama: string
{
    case ISLAM = 'Islam';
    case KRISTEN = 'Kristen';
    case KATOLIK = 'Katolik';
    case HINDU = 'Hindu';
    case BUDDHA = 'Buddha';

    public function label(): string
    {
        return match($this) {
            self::ISLAM => 'Islam',
            self::KRISTEN => 'Kristen',
            self::KATOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
        };
    }

    public static function options(): array
    {
        return [
            self::ISLAM->value => self::ISLAM->label(),
            self::KRISTEN->value => self::KRISTEN->label(),
            self::KATOLIK->value => self::KATOLIK->label(),
            self::HINDU->value => self::HINDU->label(),
            self::BUDDHA->value => self::BUDDHA->label(),
        ];
    }
}
