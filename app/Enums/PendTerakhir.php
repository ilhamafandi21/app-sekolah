<?php

namespace App\Enums;

enum PendTerakhir: string
{
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA_SMK = 'SMA_SMK';
    case PERGURUAN_TINGGI = 'PERGURUAN_TINGGI';

    public function label(): string
    {
        return match($this) {
            self::SD => 'SD',
            self::SMP => 'SMP',
            self::SMA_SMK => 'SMA_SMK',
            self::PERGURUAN_TINGGI => 'PERGURUAN_TINGGI',
        };
    }

    public static function options(): array
    {
        return [
            self::SD->value => self::SD->label(),
            self::SMP->value => self::SMP->label(),
            self::SMA_SMK->value => self::SMA_SMK->label(),
            self::PERGURUAN_TINGGI->value => self::PERGURUAN_TINGGI->label(),
        ];
    }
}