<?php

namespace App\Support\Validation;

use Illuminate\Validation\Rules\Closure as ClosureRule;

class TahunAjaranRules
{
    public static function name(): array
    {
        return [
            'regex:/^\d{4}-\d{4}$/',
            ClosureRule::make(function ($data, $attribute, $value, $fail) {
                if (preg_match('/^(\d{4})-(\d{4})$/', $value, $match)) {
                    if ((int)$match[2] !== (int)$match[1] + 1) {
                        $fail('Tahun kedua harus satu tahun setelah tahun pertama. Contoh: 2022-2023');
                    }
                }
            }),
        ];
    }

    public static function startDate(): ClosureRule
    {
        return ClosureRule::make(function ($data, $attribute, $value, $fail) {
            if (!empty($data['end_date']) && $value > $data['end_date']) {
                $fail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
            }
        });
    }

    public static function endDate(): ClosureRule
    {
        return ClosureRule::make(function ($data, $attribute, $value, $fail) {
            if (!empty($data['start_date']) && $value < $data['start_date']) {
                $fail('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.');
            }
        });
    }
}
