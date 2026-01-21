<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'specialization',
        'attribute_type',
        'data_source',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relasi ke AHP Matrices (sebagai baris)
     */
    public function ahpMatricesAsRow(): HasMany
    {
        return $this->hasMany(AhpMatrix::class, 'criteria_row_id');
    }

    /**
     * Relasi ke AHP Matrices (sebagai kolom)
     */
    public function ahpMatricesAsCol(): HasMany
    {
        return $this->hasMany(AhpMatrix::class, 'criteria_col_id');
    }

    /**
     * Relasi ke Criterion Weights
     */
    public function weights(): HasMany
    {
        return $this->hasMany(CriterionWeight::class, 'criteria_id');
    }

    /**
     * Relasi ke Student Criterion Values
     */
    public function studentValues(): HasMany
    {
        return $this->hasMany(StudentCriterionValue::class, 'criteria_id');
    }

    /**
     * Scope: Filter by specialization
     */
    public function scopeForSpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    /**
     * Scope: Only active criteria
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}