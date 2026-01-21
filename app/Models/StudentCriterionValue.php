<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCriterionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'criteria_id',
        'raw_value',
        'normalized_value',
        'notes',
    ];

    protected $casts = [
        'raw_value' => 'decimal:2',
        'normalized_value' => 'decimal:8',
    ];

    /**
     * Relasi ke Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke Criteria
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Scope: Filter by student
     */
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope: Filter by criteria
     */
    public function scopeForCriteria($query, int $criteriaId)
    {
        return $query->where('criteria_id', $criteriaId);
    }
}