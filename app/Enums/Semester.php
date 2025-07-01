<?php

namespace App\Enums;

enum Semester: string
{
    case SEMESTER_1 = 'semester_1';
    case SEMESTER_2 = 'semester_2';

    public function label(): string
    {
        return match($this) {
            self::SEMESTER_1 => 'SEMESTER_1',
            self::SEMESTER_2 => 'SEMESTER_2',
        };
    }

    public static function options(): array
    {
        return [
            self::SEMESTER_1->value => self::SEMESTER_1->label(),
            self::SEMESTER_2->value => self::SEMESTER_2->label(),
        ];
    }
}
