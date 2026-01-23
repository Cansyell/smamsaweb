<?php

namespace App\Service;

use App\Models\AhpMatrix;
use App\Models\CriterionWeight;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AhpService
{
    public function getMatrixForDisplay(int $academicYearId, string $specialization): array
    {
        $data = AhpMatrix::getMatrixData($academicYearId, $specialization);

        return [
            'criterias' => $data['criterias'],
            'matrixArray' => $data['matrixArray'],
            'isComplete' => AhpMatrix::isMatrixComplete($academicYearId, $specialization),
            'consistencyRatio' => AhpMatrix::calculateConsistencyRatio($academicYearId, $specialization),
            'weights' => AhpMatrix::getPriorityWeights($academicYearId, $specialization),
        ];
    }

    public function saveComparison(array $data): bool
    {
        try {
            DB::transaction(function () use ($data) {
                AhpMatrix::updateOrCreate(
                    [
                        'academic_year_id' => $data['academic_year_id'],
                        'specialization' => $data['specialization'],
                        'criteria_row_id' => $data['criteria_row_id'],
                        'criteria_col_id' => $data['criteria_col_id'],
                    ],
                    [
                        'comparison_value' => $data['comparison_value'],
                        'notes' => $data['notes'] ?? null,
                    ]
                );

                if ($data['criteria_row_id'] !== $data['criteria_col_id']) {
                    AhpMatrix::updateOrCreate(
                        [
                            'academic_year_id' => $data['academic_year_id'],
                            'specialization' => $data['specialization'],
                            'criteria_row_id' => $data['criteria_col_id'],
                            'criteria_col_id' => $data['criteria_row_id'],
                        ],
                        [
                            'comparison_value' => 1 / $data['comparison_value'],
                            'notes' => $data['notes'] ?? null,
                        ]
                    );
                }
            });

            return true;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function calculateAndSaveWeights(int $academicYearId, string $specialization): bool
    {
        try {
            $weights = AhpMatrix::getPriorityWeights($academicYearId, $specialization);

            if (!$weights) {
                throw new \Exception('Matrix belum lengkap');
            }

            DB::transaction(function () use ($academicYearId, $specialization, $weights) {
                CriterionWeight::where('academic_year_id', $academicYearId)->delete();

                foreach ($weights as $criteriaId => $data) {
                    CriterionWeight::create([
                        'academic_year_id' => $academicYearId,
                        'criteria_id' => $criteriaId,
                        'weight_value' => $data['weight'],
                        'calculation_method' => 'ahp',
                    ]);
                }
            });

            return true;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function resetMatrix(int $academicYearId, string $specialization): bool
    {
        return AhpMatrix::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->delete();
    }

    public function validateConsistency(int $academicYearId, string $specialization): array
    {
        $cr = AhpMatrix::calculateConsistencyRatio($academicYearId, $specialization);

        if ($cr === null) {
            return ['valid' => false, 'cr' => null];
        }

        return [
            'valid' => $cr <= 0.1,
            'cr' => $cr,
        ];
    }

    public function getComparisonScale(): array
    {
        return [
            1 => 'Sama penting',
            3 => 'Lebih penting',
            5 => 'Sangat lebih penting',
            7 => 'Jelas lebih penting',
            9 => 'Mutlak lebih penting',
        ];
    }
}
