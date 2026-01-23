<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
     * Relasi ke Criteria (Row)
     */
    public function criteriaRow(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_row_id');
    }

    /**
     * Relasi ke Criteria (Column)
     */
    public function criteriaCol(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_col_id');
    }

    /**
     * Scope: Filter by academic year
     */
    public function scopeForAcademicYear(Builder $query, int $academicYearId): Builder
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Scope: Filter by specialization
     */
    public function scopeForSpecialization(Builder $query, string $specialization): Builder
    {
        return $query->where('specialization', $specialization);
    }

    /**
     * Scope: With all relations
     */
    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['criteriaRow', 'criteriaCol', 'academicYear']);
    }

    /**
     * Get matrix for specific academic year and specialization
     */
    public static function getMatrixData(int $academicYearId, string $specialization): array
    {
        $criterias = Criteria::forSpecialization($specialization)
            ->active()
            ->ordered()
            ->get();

        $matrices = self::forAcademicYear($academicYearId)
            ->forSpecialization($specialization)
            ->get()
            ->keyBy(function ($item) {
                return $item->criteria_row_id . '-' . $item->criteria_col_id;
            });

        $matrixArray = [];
        foreach ($criterias as $row) {
            $matrixRow = [];
            foreach ($criterias as $col) {
                $key = $row->id . '-' . $col->id;
                $matrixRow[$col->id] = $matrices->get($key)?->comparison_value ?? null;
            }
            $matrixArray[$row->id] = $matrixRow;
        }

        return [
            'criterias' => $criterias,
            'matrices' => $matrices,
            'matrixArray' => $matrixArray,
        ];
    }

    /**
     * Check if matrix is complete
     */
    public static function isMatrixComplete(int $academicYearId, string $specialization): bool
    {
        $criteriaCount = Criteria::forSpecialization($specialization)
            ->active()
            ->count();

        if ($criteriaCount === 0) {
            return false;
        }

        $requiredComparisons = ($criteriaCount * ($criteriaCount - 1)) / 2;
        
        $existingComparisons = self::forAcademicYear($academicYearId)
            ->forSpecialization($specialization)
            ->where('criteria_row_id', '<', \DB::raw('criteria_col_id'))
            ->count();

        return $existingComparisons >= $requiredComparisons;
    }

    /**
     * Calculate consistency ratio for the matrix
     */
    public static function calculateConsistencyRatio(int $academicYearId, string $specialization): ?float
    {
        $data = self::getMatrixData($academicYearId, $specialization);
        $criterias = $data['criterias'];
        $matrix = $data['matrixArray'];

        if ($criterias->isEmpty()) {
            return null;
        }

        $n = $criterias->count();
        
        // Consistency Index values
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];

        if ($n < 3 || $n > 10 || !isset($ri[$n - 1])) {
            return null;
        }

        // Build complete matrix with reciprocal values
        $completeMatrix = [];
        foreach ($criterias as $i => $rowCriteria) {
            foreach ($criterias as $j => $colCriteria) {
                if ($rowCriteria->id === $colCriteria->id) {
                    $completeMatrix[$i][$j] = 1;
                } elseif (isset($matrix[$rowCriteria->id][$colCriteria->id])) {
                    $completeMatrix[$i][$j] = $matrix[$rowCriteria->id][$colCriteria->id];
                } elseif (isset($matrix[$colCriteria->id][$rowCriteria->id])) {
                    $completeMatrix[$i][$j] = 1 / $matrix[$colCriteria->id][$rowCriteria->id];
                } else {
                    return null; // Matrix incomplete
                }
            }
        }

        // Calculate column sums
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $completeMatrix[$i][$j];
            }
        }

        // Normalize matrix
        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $completeMatrix[$i][$j] / $columnSums[$j];
            }
        }

        // Calculate priority vector (average of each row)
        $priorityVector = [];
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $normalizedMatrix[$i][$j];
            }
            $priorityVector[$i] = $sum / $n;
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($j = 0; $j < $n; $j++) {
            $weightedSum = 0;
            for ($i = 0; $i < $n; $i++) {
                $weightedSum += $completeMatrix[$i][$j] * $priorityVector[$i];
            }
            $lambdaMax += $weightedSum / $priorityVector[$j];
        }
        $lambdaMax = $lambdaMax / $n;

        // Calculate CI and CR
        $ci = ($lambdaMax - $n) / ($n - 1);
        $cr = $ci / $ri[$n - 1];

        return round($cr, 4);
    }

    /**
     * Get priority weights from matrix
     */
    public static function getPriorityWeights(int $academicYearId, string $specialization): ?array
    {
        $data = self::getMatrixData($academicYearId, $specialization);
        $criterias = $data['criterias'];
        $matrix = $data['matrixArray'];

        if ($criterias->isEmpty()) {
            return null;
        }

        $n = $criterias->count();

        // Build complete matrix
        $completeMatrix = [];
        foreach ($criterias as $i => $rowCriteria) {
            foreach ($criterias as $j => $colCriteria) {
                if ($rowCriteria->id === $colCriteria->id) {
                    $completeMatrix[$i][$j] = 1;
                } elseif (isset($matrix[$rowCriteria->id][$colCriteria->id])) {
                    $completeMatrix[$i][$j] = $matrix[$rowCriteria->id][$colCriteria->id];
                } elseif (isset($matrix[$colCriteria->id][$rowCriteria->id])) {
                    $completeMatrix[$i][$j] = 1 / $matrix[$colCriteria->id][$rowCriteria->id];
                } else {
                    return null;
                }
            }
        }

        // Calculate column sums
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $completeMatrix[$i][$j];
            }
        }

        // Normalize and calculate priority vector
        $weights = [];
        $criteriasArray = $criterias->values();
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $completeMatrix[$i][$j] / $columnSums[$j];
            }
            $weights[$criteriasArray[$i]->id] = [
                'criteria' => $criteriasArray[$i],
                'weight' => round($sum / $n, 4),
            ];
        }

        return $weights;
    }
}