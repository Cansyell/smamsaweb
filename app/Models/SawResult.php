<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SawResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'specialization',
        'final_score',
        'rank',
        'detail_calculation',
        'calculated_at',
        'calculated_by',
    ];

    protected $casts = [
        'final_score' => 'decimal:8',
        'rank' => 'integer',
        'detail_calculation' => 'array',
        'calculated_at' => 'datetime',
    ];

    /**
     * Relasi ke Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke Academic Year
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke User (calculator)
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
     * Scope: Order by rank
     */
    public function scopeRanked($query)
    {
        return $query->orderBy('rank');
    }

    /**
     * Scope: Order by final score descending
     */
    public function scopeByScore($query)
    {
        return $query->orderBy('final_score', 'desc');
    }
}