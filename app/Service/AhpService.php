<?php

namespace App\Services;

use App\Models\AhpMatrix;
use App\Models\Criteria;
use App\Models\CriterionWeight;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AhpService
{
    /**
     * Random Index (RI) untuk consistency ratio
     */
    private const RANDOM_INDEX = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
        11 => 1.51,
        12 => 1.48,
        13 => 1.56,
        14 => 1.57,
        15 => 1.59,
    ];

    /**
     * Hitung bobot kriteria menggunakan metode AHP
     *
     * @param int $academicYearId
     * @param string $specialization
     * @param int|null $calculatedBy User ID yang melakukan kalkulasi
     * @return array
     */
    public function calculateWeights(int $academicYearId, string $specialization, ?int $calculatedBy = null): array
    {
        try {
            DB::beginTransaction();

            // 1. Ambil semua kriteria aktif untuk spesializasi ini
            $criterias = Criteria::active()
                ->forSpecialization($specialization)
                ->ordered()
                ->get();

            if ($criterias->isEmpty()) {
                throw new \Exception("Tidak ada kriteria aktif untuk spesializasi {$specialization}");
            }

            $n = $criterias->count();
            $criteriaIds = $criterias->pluck('id')->toArray();

            // 2. Ambil matriks perbandingan dari database
            $comparisons = AhpMatrix::forAcademicYearAndSpecialization($academicYearId, $specialization)
                ->get()
                ->keyBy(function ($item) {
                    return $item->criteria_row_id . '-' . $item->criteria_col_id;
                });

            // 3. Bangun matriks perbandingan berpasangan (pairwise comparison matrix)
            $matrix = $this->buildComparisonMatrix($criteriaIds, $comparisons);

            // 4. Normalisasi matriks
            $normalizedMatrix = $this->normalizeMatrix($matrix);

            // 5. Hitung priority vector (rata-rata baris dari normalized matrix)
            $priorityVector = $this->calculatePriorityVector($normalizedMatrix);

            // 6. Hitung consistency
            $consistency = $this->calculateConsistency($matrix, $priorityVector, $n);

            // 7. Simpan hasil ke database
            $weights = [];
            foreach ($criterias as $index => $criteria) {
                $weight = CriterionWeight::updateOrCreate(
                    [
                        'academic_year_id' => $academicYearId,
                        'criteria_id' => $criteria->id,
                        'specialization' => $specialization,
                    ],
                    [
                        'weight' => $priorityVector[$index],
                        'priority_vector' => $priorityVector[$index],
                        'lambda_max' => $consistency['lambda_max'],
                        'consistency_index' => $consistency['ci'],
                        'consistency_ratio' => $consistency['cr'],
                        'is_consistent' => $consistency['is_consistent'],
                        'calculation_detail' => [
                            'matrix' => $matrix,
                            'normalized_matrix' => $normalizedMatrix,
                            'priority_vector' => $priorityVector,
                        ],
                        'calculated_at' => now(),
                        'calculated_by' => $calculatedBy,
                    ]
                );

                $weights[] = $weight;
            }

            DB::commit();

            return [
                'success' => true,
                'data' => [
                    'weights' => $weights,
                    'consistency' => $consistency,
                    'criterias' => $criterias,
                ],
                'message' => $consistency['is_consistent'] 
                    ? 'Perhitungan AHP berhasil dan konsisten (CR <= 0.1)'
                    : "Perhitungan AHP berhasil tetapi TIDAK konsisten (CR = {$consistency['cr']} > 0.1). Harap periksa kembali matriks perbandingan."
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AHP Calculation Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal menghitung bobot AHP: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bangun matriks perbandingan dari data di database
     */
    private function buildComparisonMatrix(array $criteriaIds, $comparisons): array
    {
        $n = count($criteriaIds);
        $matrix = array_fill(0, $n, array_fill(0, $n, 0));

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    // Diagonal = 1
                    $matrix[$i][$j] = 1;
                } else {
                    $key = $criteriaIds[$i] . '-' . $criteriaIds[$j];
                    
                    if (isset($comparisons[$key])) {
                        // Ada data perbandingan langsung
                        $matrix[$i][$j] = (float) $comparisons[$key]->comparison_value;
                    } else {
                        // Reciprocal dari perbandingan kebalikan
                        $reverseKey = $criteriaIds[$j] . '-' . $criteriaIds[$i];
                        if (isset($comparisons[$reverseKey])) {
                            $matrix[$i][$j] = 1 / (float) $comparisons[$reverseKey]->comparison_value;
                        } else {
                            throw new \Exception("Data perbandingan tidak lengkap untuk kriteria {$criteriaIds[$i]} vs {$criteriaIds[$j]}");
                        }
                    }
                }
            }
        }

        return $matrix;
    }

    /**
     * Normalisasi matriks (setiap kolom dibagi dengan jumlah kolom)
     */
    private function normalizeMatrix(array $matrix): array
    {
        $n = count($matrix);
        $normalizedMatrix = array_fill(0, $n, array_fill(0, $n, 0));

        // Hitung jumlah setiap kolom
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalisasi
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Hitung priority vector (rata-rata setiap baris dari normalized matrix)
     */
    private function calculatePriorityVector(array $normalizedMatrix): array
    {
        $n = count($normalizedMatrix);
        $priorityVector = [];

        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $priorityVector[$i] = $rowSum / $n;
        }

        return $priorityVector;
    }

    /**
     * Hitung consistency (λmax, CI, CR)
     */
    private function calculateConsistency(array $matrix, array $priorityVector, int $n): array
    {
        // Hitung weighted sum vector
        $weightedSum = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $priorityVector[$j];
            }
        }

        // Hitung λmax
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($priorityVector[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $priorityVector[$i];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        // Hitung CI (Consistency Index)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Hitung CR (Consistency Ratio)
        $ri = self::RANDOM_INDEX[$n] ?? 1.49;
        $cr = $ri != 0 ? $ci / $ri : 0;

        return [
            'lambda_max' => round($lambdaMax, 8),
            'ci' => round($ci, 8),
            'cr' => round($cr, 8),
            'ri' => $ri,
            'is_consistent' => $cr <= 0.1,
        ];
    }

    /**
     * Validasi apakah matriks perbandingan sudah lengkap
     */
    public function validateMatrix(int $academicYearId, string $specialization): array
    {
        $criterias = Criteria::active()
            ->forSpecialization($specialization)
            ->get();

        $n = $criterias->count();
        $requiredComparisons = ($n * ($n - 1)) / 2; // Jumlah perbandingan yang dibutuhkan (tanpa diagonal)

        $existingComparisons = AhpMatrix::forAcademicYearAndSpecialization($academicYearId, $specialization)
            ->count();

        $isComplete = $existingComparisons >= $requiredComparisons;

        return [
            'is_complete' => $isComplete,
            'total_criteria' => $n,
            'required_comparisons' => $requiredComparisons,
            'existing_comparisons' => $existingComparisons,
            'missing_comparisons' => max(0, $requiredComparisons - $existingComparisons),
        ];
    }

    /**
     * Get current weights untuk academic year dan specialization tertentu
     */
    public function getWeights(int $academicYearId, string $specialization): array
    {
        $weights = CriterionWeight::with('criteria')
            ->forAcademicYearAndSpecialization($academicYearId, $specialization)
            ->get();

        return $weights->mapWithKeys(function ($weight) {
            return [$weight->criteria->code => (float) $weight->weight];
        })->toArray();
    }
}
