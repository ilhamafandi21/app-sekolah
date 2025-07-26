<?php

namespace App\Enums;

enum SemesterEnum: int
{
    case GANJIL = 1;
    case GENAP = 2;

    public function label(): string
    {
        return match($this) {
            self::GANJIL => 'GANJIL',
            self::GENAP => 'GENAP',
        };
    }

    public static function options(): array
    {
        return [
            self::GANJIL->value => self::GANJIL->label(),
            self::GENAP->value => self::GENAP->label(),
        ];
    }
}
