<?php

namespace App\Service;

use App\Models\Criteria;
use App\Models\CriterionWeight;
use App\Models\SawResult;
use App\Models\Student;
use App\Models\StudentCriterionValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SawService
{
    // =========================================================================
    // PUBLIC
    // =========================================================================

    /**
     * Hitung SAW score untuk SEMUA siswa (tahfiz & language) di KEDUA track.
     *
     * Setiap siswa mendapat 2 SAW result:
     *   - 1 hasil di track tahfiz
     *   - 1 hasil di track language
     *
     * Prioritas lulus ditentukan oleh RankingService (Pass 1–4), bukan di sini.
     */
    public function calculateAllScores(int $academicYearId, ?int $calculatedBy = null): array
    {
        try {
            DB::beginTransaction();

            $results = [];

            foreach (['tahfiz', 'language'] as $specialization) {
                $result = $this->calculateScoresForSpecialization(
                    $academicYearId,
                    $specialization,
                    $calculatedBy
                );

                $results[$specialization] = $result;

                if (!$result['success']) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => "Gagal menghitung SAW untuk {$specialization}: {$result['message']}",
                        'data'    => $results,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'data'    => [
                    'tahfiz'   => $results['tahfiz']['data'],
                    'language' => $results['language']['data'],
                ],
                'message' => sprintf(
                    'Perhitungan SAW berhasil! Track Tahfiz: %d siswa, Track Language: %d siswa.',
                    $results['tahfiz']['data']['total_students']   ?? 0,
                    $results['language']['data']['total_students'] ?? 0
                ),
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('SAW Calculation Error (All): ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
            ];
        }
    }

    /**
     * Get ranking untuk specialization tertentu.
     */
    public function getRankings(int $academicYearId, string $specialization, ?int $limit = null): array
    {
        $query = SawResult::with(['student', 'student.user'])
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->orderBy('rank');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->toArray();
    }

    /**
     * Get SAW score siswa untuk semua specialization.
     */
    public function getStudentAllScores(int $studentId, int $academicYearId): array
    {
        $results = SawResult::with(['student', 'academicYear'])
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->keyBy('specialization');

        return [
            'tahfiz'   => $results->get('tahfiz'),
            'language' => $results->get('language'),
        ];
    }

    /**
     * Get SAW score detail siswa untuk satu specialization.
     */
    public function getStudentScoreDetail(int $studentId, int $academicYearId, string $specialization): ?array
    {
        $result = SawResult::with(['student', 'academicYear'])
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->first();

        if (!$result) {
            return null;
        }

        return [
            'student'            => $result->student,
            'academic_year'      => $result->academicYear,
            'specialization'     => $result->specialization,
            'final_score'        => $result->final_score,
            'rank'               => $result->rank,
            'detail_calculation' => $result->detail_calculation,
            'calculated_at'      => $result->calculated_at,
        ];
    }

    // =========================================================================
    // PRIVATE
    // =========================================================================

    /**
     * Hitung SAW score untuk satu track specialization.
     *
     * SEMUA siswa valid (tahfiz & language) dihitung di track ini,
     * karena setiap siswa harus punya 2 SAW result untuk keperluan
     * Pass 2 (cross-specialization) dan Pass 4 (rekomendasi pindah).
     *
     * Syarat: setiap siswa harus sudah memiliki nilai untuk semua
     * criteria aktif pada track yang dihitung.
     */
    private function calculateScoresForSpecialization(
        int $academicYearId,
        string $specialization,
        ?int $calculatedBy = null
    ): array {
        try {
            // ----------------------------------------------------------------
            // 1. Criteria aktif untuk track ini = sumber kebenaran
            // ----------------------------------------------------------------
            $activeCriterias = Criteria::active()
                ->where('specialization', $specialization)
                ->ordered()
                ->get();

            if ($activeCriterias->isEmpty()) {
                throw new \RuntimeException(
                    "Tidak ada kriteria aktif untuk specialization '{$specialization}'. " .
                    "Tambahkan dan aktifkan kriteria terlebih dahulu."
                );
            }

            $activeCriteriaIds = $activeCriterias->pluck('id')->sort()->values()->toArray();

            Log::info("SAW [{$specialization}] Criteria aktif: " . implode(', ', $activeCriteriaIds));

            // ----------------------------------------------------------------
            // 2. Bobot AHP — hanya untuk criteria aktif
            // ----------------------------------------------------------------
            $weights = CriterionWeight::with('criteria')
                ->forAcademicYearAndSpecialization($academicYearId, $specialization)
                ->consistent()
                ->whereIn('criteria_id', $activeCriteriaIds)
                ->get();

            if ($weights->isEmpty()) {
                throw new \RuntimeException(
                    "Bobot AHP belum tersedia atau tidak konsisten untuk specialization '{$specialization}'. " .
                    "Pastikan perhitungan AHP sudah dilakukan dengan CR ≤ 0.1."
                );
            }

            // Criteria aktif yang belum punya bobot AHP → skip dengan warning
            $weightedCriteriaIds = $weights->pluck('criteria_id')->sort()->values()->toArray();
            $unweightedIds       = collect($activeCriteriaIds)->diff($weightedCriteriaIds)->values();

            if ($unweightedIds->isNotEmpty()) {
                $unweightedNames = $activeCriterias
                    ->whereIn('id', $unweightedIds->toArray())
                    ->pluck('name')
                    ->implode(', ');

                Log::warning(
                    "SAW [{$specialization}] Criteria aktif tanpa bobot AHP (diabaikan): {$unweightedNames}",
                    ['criteria_ids' => $unweightedIds->toArray()]
                );
            }

            // Criteria yang benar-benar dihitung = aktif + punya bobot AHP
            $calculatedCriteriaIds = $weightedCriteriaIds;

            Log::info("SAW [{$specialization}] Criteria yang dihitung: " . implode(', ', $calculatedCriteriaIds));

            // ----------------------------------------------------------------
            // 3. Ambil SEMUA siswa valid (tahfiz & language) — bukan hanya
            //    yang memilih specialization ini, karena setiap siswa perlu
            //    2 SAW result untuk Pass 2 & Pass 4.
            // ----------------------------------------------------------------
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->whereIn('specialization', ['tahfiz', 'language'])
                ->get();

            if ($students->isEmpty()) {
                throw new \RuntimeException(
                    "Tidak ada siswa valid (tahfiz/language) untuk tahun ajaran ini."
                );
            }

            Log::info("SAW [{$specialization}] Total siswa yang dihitung: {$students->count()}");

            // ----------------------------------------------------------------
            // 4. Validasi: setiap siswa harus punya nilai untuk semua
            //    criteria yang dihitung di track ini
            // ----------------------------------------------------------------
            $this->validateStudentValues($students, $calculatedCriteriaIds, $specialization);

            // ----------------------------------------------------------------
            // 5. Ambil semua nilai yang dibutuhkan
            // ----------------------------------------------------------------
            $studentIds = $students->pluck('id')->toArray();

            $allValues = StudentCriterionValue::whereIn('student_id', $studentIds)
                ->whereIn('criteria_id', $calculatedCriteriaIds)
                ->get()
                ->groupBy('criteria_id');

            // ----------------------------------------------------------------
            // 6. Normalisasi nilai per kriteria
            // ----------------------------------------------------------------
            $normalizedValues = $this->normalizeValues($allValues, $weights);

            // ----------------------------------------------------------------
            // 7. Hitung SAW score per siswa & simpan
            // ----------------------------------------------------------------
            $savedResults = [];

            foreach ($students as $student) {
                $sawScore = $this->calculateStudentScore($student->id, $weights, $normalizedValues);

                $savedResults[] = SawResult::updateOrCreate(
                    [
                        'student_id'       => $student->id,
                        'academic_year_id' => $academicYearId,
                        'specialization'   => $specialization,
                    ],
                    [
                        'final_score'        => $sawScore['final_score'],
                        'detail_calculation' => $sawScore['details'],
                        'calculated_at'      => now(),
                        'calculated_by'      => $calculatedBy,
                    ]
                );
            }

            // ----------------------------------------------------------------
            // 8. Update ranking — rank global semua siswa di track ini
            // ----------------------------------------------------------------
            $this->updateRankings($academicYearId, $specialization);

            Log::info("SAW [{$specialization}] Selesai: {$students->count()} siswa dihitung.");

            return [
                'success' => true,
                'data'    => [
                    'total_students'          => count($savedResults),
                    'active_criteria_count'   => count($activeCriteriaIds),
                    'weighted_criteria_count' => count($calculatedCriteriaIds),
                    'unweighted_criteria'     => $unweightedIds->toArray(),
                    'results'                 => $savedResults,
                ],
                'message' => "Perhitungan SAW berhasil untuk track {$specialization}: " . count($savedResults) . " siswa.",
            ];

        } catch (\Throwable $e) {
            Log::error("SAW Calculation Error [{$specialization}]: " . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
            ];
        }
    }

    /**
     * Validasi bahwa setiap siswa memiliki nilai lengkap untuk semua criteria
     * yang akan dihitung pada track ini.
     *
     * @throws \RuntimeException dengan detail siswa & criteria yang kurang
     */
    private function validateStudentValues(
        \Illuminate\Support\Collection $students,
        array $criteriaIds,
        string $specialization
    ): void {
        $errors = [];

        foreach ($students as $student) {
            $ownedIds = StudentCriterionValue::where('student_id', $student->id)
                ->whereIn('criteria_id', $criteriaIds)
                ->pluck('criteria_id')
                ->sort()
                ->values();

            $missingIds = collect($criteriaIds)->diff($ownedIds)->values();

            if ($missingIds->isEmpty()) {
                continue;
            }

            $missingNames = Criteria::whereIn('id', $missingIds)
                ->pluck('name')
                ->implode(', ');

            $errors[] = sprintf(
                "  • %s (NISN: %s, pilihan: %s) — missing: %s [ID: %s]",
                $student->full_name,
                $student->nisn,
                $student->specialization,
                $missingNames,
                $missingIds->implode(', ')
            );
        }

        if (!empty($errors)) {
            throw new \RuntimeException(
                "Siswa berikut belum memiliki nilai lengkap untuk track [{$specialization}]:\n" .
                implode("\n", $errors) . "\n\n" .
                "Lengkapi nilai kriteria sebelum menghitung SAW."
            );
        }
    }

    /**
     * Normalisasi nilai berdasarkan tipe atribut (benefit / cost).
     *   Benefit : r_ij = x_ij / max(x_ij)
     *   Cost    : r_ij = min(x_ij) / x_ij
     */
    private function normalizeValues(
        \Illuminate\Support\Collection $allValues,
        \Illuminate\Support\Collection $weights
    ): array {
        $normalized = [];

        foreach ($weights as $weight) {
            $criteriaId    = $weight->criteria_id;
            $attributeType = $weight->criteria->attribute_type;

            if (!isset($allValues[$criteriaId])) {
                continue;
            }

            $values    = $allValues[$criteriaId];
            $rawValues = $values->pluck('raw_value')->map(fn ($v) => (float) $v)->toArray();

            if ($attributeType === 'benefit') {
                $maxValue = max($rawValues);

                foreach ($values as $value) {
                    $norm = $maxValue > 0 ? (float) $value->raw_value / $maxValue : 0.0;
                    $value->update(['normalized_value' => $norm]);
                    $normalized[$criteriaId][$value->student_id] = $norm;
                }
            } else {
                // cost
                $minValue = min($rawValues);

                foreach ($values as $value) {
                    $norm = (float) $value->raw_value > 0 ? $minValue / (float) $value->raw_value : 0.0;
                    $value->update(['normalized_value' => $norm]);
                    $normalized[$criteriaId][$value->student_id] = $norm;
                }
            }
        }

        return $normalized;
    }

    /**
     * Hitung SAW score akhir untuk satu siswa.
     * V_i = Σ (w_j × r_ij)
     */
    private function calculateStudentScore(
        int $studentId,
        \Illuminate\Support\Collection $weights,
        array $normalizedValues
    ): array {
        $finalScore = 0.0;
        $details    = [];

        foreach ($weights as $weight) {
            $criteriaId   = $weight->criteria_id;
            $criteriaCode = $weight->criteria->code;
            $w            = (float) $weight->weight;
            $r            = $normalizedValues[$criteriaId][$studentId] ?? 0.0;
            $score        = $w * $r;

            $finalScore += $score;

            $details[$criteriaCode] = [
                'criteria_name'    => $weight->criteria->name,
                'weight'           => $w,
                'normalized_value' => $r,
                'score'            => $score,
            ];
        }

        return [
            'final_score' => round($finalScore, 8),
            'details'     => $details,
        ];
    }

    /**
     * Update ranking global semua siswa di track ini berdasarkan final_score (desc).
     * Rank ini mencerminkan posisi kompetitif di track tersebut,
     * terlepas dari specialization pilihan siswa.
     */
    private function updateRankings(int $academicYearId, string $specialization): void
    {
        $results = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->orderByDesc('final_score')
            ->get();

        // --- Rank global (semua siswa di track ini, termasuk cross) ---
        $rank = 1;
        foreach ($results as $result) {
            $result->update(['rank' => $rank++]);
        }

        // --- Primary rank (hanya siswa yang MEMILIH specialization ini) ---
        // Ini yang dipakai untuk menentukan lulus/tidak di jalur primer
        $primaryResults = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->whereHas('student', fn($q) => $q->where('specialization', $specialization))
            ->orderByDesc('final_score')
            ->get();

        $primaryRank = 1;
        foreach ($primaryResults as $result) {
            $result->update(['primary_rank' => $primaryRank++]);
        }

        // Siswa cross (tidak memilih specialization ini) → primary_rank = null
        SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->whereHas('student', fn($q) => $q->where('specialization', '!=', $specialization))
            ->update(['primary_rank' => null]);
    }
}