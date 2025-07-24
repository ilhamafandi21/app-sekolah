<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Semester extends Model
{
    protected $fillable = [
        'name',
        'tahun_ajaran_id',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Format name jadi Title Case
            $model->name = ucfirst(strtolower(trim($model->name)));

            // Tambahkan created_by dan updated_by
            if (Auth::check()) {
                if (!$model->exists) {
                    $model->created_by = Auth::user()->name;
                }
                $model->updated_by = Auth::user()->name;
            }
        });
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
