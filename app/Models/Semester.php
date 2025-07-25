<?php

namespace App\Models;

use App\Models\TahunAjaran;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

            if ($model->is_active) {
                static::where('tahun_ajaran_id', $model->tahun_ajaran_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_active' => false]);
            }
        });
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function getLabelAttribute()
    {
        return "{$this->name} - {$this->tahunAjaran->name}";
    }

}
