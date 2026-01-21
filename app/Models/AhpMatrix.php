<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'specialization',
        'criteria_row_id',
        'criteria_col_id',
        'comparison_value',
        'notes',
    ];

    protected $casts = [
        'comparison_value' => 'decimal:6',
    ];

    /**
     * Relasi ke Academic Year
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke Criteria (baris)
     */
    public function criteriaRow(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_row_id');
    }

    /**
     * Relasi ke Criteria (kolom)
     */
    public function criteriaCol(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_col_id');
    }

    /**
     * Scope: Filter by academic year and specialization
     */
    public function scopeForAcademicYearAndSpecialization($query, int $academicYearId, string $specialization)
    {
        return $query->where('academic_year_id', $academicYearId)
                     ->where('specialization', $specialization);
    }
}