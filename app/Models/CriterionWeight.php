<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriterionWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'criteria_id',
        'specialization',
        'weight',
        'priority_vector',
        'lambda_max',
        'consistency_index',
        'consistency_ratio',
        'is_consistent',
        'calculation_detail',
        'calculated_at',
        'calculated_by',
    ];

    protected $casts = [
        'weight' => 'decimal:8',
        'priority_vector' => 'decimal:8',
        'lambda_max' => 'decimal:8',
        'consistency_index' => 'decimal:8',
        'consistency_ratio' => 'decimal:8',
        'is_consistent' => 'boolean',
        'calculation_detail' => 'array',
        'calculated_at' => 'datetime',
    ];

    /**
     * Relasi ke Academic Year
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke Criteria
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Relasi ke User (yang melakukan kalkulasi)
     */
    public function calculator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }

    /**
     * Scope: Filter by academic year and specialization
     */
    public function scopeForAcademicYearAndSpecialization($query, int $academicYearId, string $specialization)
    {
        return $query->where('academic_year_id', $academicYearId)
                     ->where('specialization', $specialization);
    }

    /**
     * Scope: Only consistent weights
     */
    public function scopeConsistent($query)
    {
        return $query->where('is_consistent', true);
    }
}