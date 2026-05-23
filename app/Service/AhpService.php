<?php

namespace App\Service;

use App\Models\AhpMatrix;
use App\Models\CriterionWeight;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AhpService
{
    /**
     * Ambil data mentah matriks beserta progress pengisian.
     * Tidak ada kalkulasi apapun di sini.
     */
    public function getMatrixRawData(int $academicYearId, string $specialization): array
    {
        $data      = AhpMatrix::getMatrixData($academicYearId, $specialization);
        $criterias = $data['criterias'];
        $n         = $criterias->count();

        $required = ($n * ($n - 1)) / 2;
        $filled   = 0;

        foreach ($criterias as $r) {
            foreach ($criterias as $c) {
                if ($r->id < $c->id && isset($data['matrixArray'][$r->id][$c->id])) {
                    $filled++;
                }
            }
        }

        return [
            'criterias'     => $criterias,
            'matrixArray'   => $data['matrixArray'],
            'isComplete'    => AhpMatrix::isMatrixComplete($academicYearId, $specialization),
            'filledCount'   => $filled,
            'requiredCount' => $required,
        ];
    }

    /**
     * Simpan satu nilai perbandingan beserta kebalikannya (lower-triangle).
     */
    public function saveComparison(array $data): bool
    {
        try {
            DB::transaction(function () use ($data) {
                $val = (float) $data['comparison_value'];

                AhpMatrix::updateOrCreate(
                    [
                        'academic_year_id' => $data['academic_year_id'],
                        'specialization'   => $data['specialization'],
                        'criteria_row_id'  => $data['criteria_row_id'],
                        'criteria_col_id'  => $data['criteria_col_id'],
                    ],
                    [
                        'comparison_value' => $val,
                        'notes'            => $data['notes'] ?? null,
                    ]
                );

                // Simpan nilai kebalikan untuk lower-triangle
                if ($data['criteria_row_id'] !== $data['criteria_col_id']) {
                    AhpMatrix::updateOrCreate(
                        [
                            'academic_year_id' => $data['academic_year_id'],
                            'specialization'   => $data['specialization'],
                            'criteria_row_id'  => $data['criteria_col_id'],
                            'criteria_col_id'  => $data['criteria_row_id'],
                        ],
                        [
                            'comparison_value' => $val != 0 ? round(1 / $val, 10) : 0,
                            'notes'            => $data['notes'] ?? null,
                        ]
                    );
                }
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('AHP saveComparison error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hitung semua metrik AHP, validasi konsistensi, lalu simpan ke DB.
     *
     * @return true|array{error: string}|false
     */
    public function calculateAndSaveWeights(int $academicYearId, string $specialization): bool|array
    {
        try {
            $metrics = AhpMatrix::calculateAhpMetrics($academicYearId, $specialization);

            if (! $metrics) {
                return ['error' => 'Matriks belum lengkap atau jumlah kriteria di luar rentang tabel RI (2–15).'];
            }

            if (! $metrics['consistent']) {
                return [
                    'error' => sprintf(
                        'Matriks tidak konsisten (CR = %.4f > 0.1). Perbaiki nilai perbandingan terlebih dahulu.',
                        $metrics['cr']
                    ),
                ];
            }

            DB::transaction(function () use ($academicYearId, $specialization, $metrics) {
                CriterionWeight::where('academic_year_id', $academicYearId)
                    ->where('specialization', $specialization)
                    ->delete();

                foreach ($metrics['weights'] as $criteriaId => $item) {
                    CriterionWeight::create([
                        'academic_year_id'  => $academicYearId,
                        'specialization'    => $specialization,
                        'criteria_id'       => $criteriaId,
                        'weight'            => $item['weight'],
                        'priority_vector'   => $item['weight'],
                        'consistency_ratio' => $metrics['cr'],
                        'is_consistent'     => true,
                        'calculated_at'     => now(),
                        'calculated_by'     => auth()->id(),
                    ]);
                }
            });

            Log::info('AHP weights saved', [
                'academic_year_id' => $academicYearId,
                'specialization'   => $specialization,
                'n'                => $metrics['n'],
                'lambda_max'       => round($metrics['lambdaMax'], 6),
                'ci'               => round($metrics['ci'], 6),
                'ri'               => $metrics['ri'],
                'cr'               => round($metrics['cr'], 6),
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('AHP calculateAndSaveWeights error: ' . $e->getMessage(), [
                'academic_year_id' => $academicYearId,
                'specialization'   => $specialization,
            ]);
            return false;
        }
    }

    /**
     * Reset matriks perbandingan dan bobot tersimpan untuk kombinasi
     * tahun ajaran + spesialisasi tertentu.
     */
    public function resetMatrix(int $academicYearId, string $specialization): bool
    {
        CriterionWeight::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->delete();

        return (bool) AhpMatrix::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->delete();
    }

    /**
     * Ambil hasil kalkulasi AHP yang sudah tersimpan di DB.
     * Mengembalikan null jika belum pernah dihitung dan disimpan.
     */
    public function getSavedResult(int $yearId, string $specialization): ?array
    {
        $rows = CriterionWeight::with('criteria')
            ->where('academic_year_id', $yearId)
            ->where('specialization', $specialization)
            ->orderBy('weight', 'desc')
            ->get();

        if ($rows->isEmpty()) {
            return null;
        }

        $first = $rows->first();

        return [
            'weights' => $rows->map(fn ($w) => [
                'criteria' => $w->criteria,
                'weight'   => (float) $w->weight,
            ])->values()->toArray(),
            'consistency_ratio' => (float) $first->consistency_ratio,
            'is_consistent'     => (bool) $first->is_consistent,
            'calculated_at'     => $first->calculated_at,
        ];
    }

    /**
     * Skala perbandingan AHP Saaty (1–9).
     */
    public function getComparisonScale(): array
    {
        return [
            1 => 'Sama penting',
            2 => 'Antara sama dan sedikit lebih penting',
            3 => 'Lebih penting',
            4 => 'Antara lebih dan sangat penting',
            5 => 'Sangat lebih penting',
            6 => 'Antara sangat dan jelas lebih penting',
            7 => 'Jelas lebih penting',
            8 => 'Antara jelas dan mutlak lebih penting',
            9 => 'Mutlak lebih penting',
        ];
    }
}