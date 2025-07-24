<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TahunAjaran extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'slug',
        'description',
        'created_by',
        'updated_by',
    ];

    // Jika kamu ingin konversi otomatis ke date
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        // Saat CREATE atau UPDATE (sebelum disimpan)
        static::saving(function ($model) {
            // Format NAME
            $model->name = strtoupper(str_replace(' ', '', $model->name));

            // Tambahkan created_by dan updated_by jika login
            if (Auth::check()) {
                if (!$model->exists) {
                    $model->created_by = Auth::user()->name; // atau ->id jika ingin ID
                }
                $model->updated_by = Auth::user()->name;
            }
        });

        // Saat CREATE
        static::creating(function ($model) {
            $model->slug = static::generateUniqueSlug($model->name);
        });

        // Saat UPDATE
        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = static::generateUniqueSlug($model->name, $model->id);
            }
        });
    }

    /**
     * Generate slug unik
     */
    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Scope aktif (optional)
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Format tanggal (optional accessor)
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date?->format('d M Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date?->format('d M Y');
    }
}
