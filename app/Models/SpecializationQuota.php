<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecializationQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'tahfiz_quota',
        'language_quota',
        'is_active',
    ];

    protected $casts = [
        'tahfiz_quota' => 'integer',
        'language_quota' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke academic year
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Scope untuk mendapatkan quota aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}