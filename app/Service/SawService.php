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
    /**
     * Hitung SAW score untuk SEMUA siswa di SEMUA spesialisasi (Tahfiz & Language)
     * Setiap siswa akan memiliki 2 SAW results (1 untuk Tahfiz, 1 untuk Language)
     *
     * @param int $academicYearId
     * @param int|null $calculatedBy
     * @return array
     */
    public function calculateAllScores(int $academicYearId, ?int $calculatedBy = null): array
    {
        try {
            DB::beginTransaction();

            $results = [
                'tahfiz' => ['success' => false, 'data' => [], 'message' => ''],
                'language' => ['success' => false, 'data' => [], 'message' => ''],
            ];

            // Hitung untuk KEDUA spesialisasi
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
                    ];
                }
            }

            DB::commit();

            $totalTahfiz = $results['tahfiz']['data']['total_students'] ?? 0;
            $totalLanguage = $results['language']['data']['total_students'] ?? 0;

            return [
                'success' => true,
                'data' => [
                    'tahfiz' => $results['tahfiz']['data'],
                    'language' => $results['language']['data'],
                ],
                'message' => "Perhitungan SAW berhasil! Tahfiz: {$totalTahfiz} siswa, Language: {$totalLanguage} siswa",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SAW Calculation Error (All): ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal menghitung SAW: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Hitung SAW score untuk satu specialization
     * SEMUA siswa valid akan dihitung, tidak peduli pilihan spesialisasi mereka
     *
     * @param int $academicYearId
     * @param string $specialization
     * @param int|null $calculatedBy
     * @return array
     */
    private function calculateScoresForSpecialization(
        int $academicYearId,
        string $specialization,
        ?int $calculatedBy = null
    ): array {
        try {
            // 1. Ambil bobot kriteria dari AHP untuk spesialisasi ini
            $weights = CriterionWeight::with('criteria')
                ->forAcademicYearAndSpecialization($academicYearId, $specialization)
                ->consistent()
                ->get();

            if ($weights->isEmpty()) {
                throw new \Exception("Bobot kriteria belum dihitung atau tidak konsisten untuk {$specialization}");
            }

            // 2. Ambil siswa yang BUKAN regular (hanya tahfiz & language yang perlu SAW)
            // Siswa regular langsung masuk jalur FCFS, tidak perlu SAW
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->whereIn('specialization', ['tahfiz', 'language']) // HANYA tahfiz & language
                ->get();

            if ($students->isEmpty()) {
                throw new \Exception("Tidak ada siswa yang memilih tahfiz atau language");
            }

            // 3. Ambil semua nilai siswa untuk kriteria specialization ini
            $criteriaIds = $weights->pluck('criteria_id')->toArray();
            $studentIds = $students->pluck('id')->toArray();

            $allValues = StudentCriterionValue::whereIn('student_id', $studentIds)
                ->whereIn('criteria_id', $criteriaIds)
                ->get()
                ->groupBy('criteria_id');

            // 4. Validasi: Pastikan semua siswa memiliki nilai lengkap untuk kriteria ini
            foreach ($students as $student) {
                $studentValueCount = StudentCriterionValue::where('student_id', $student->id)
                    ->whereIn('criteria_id', $criteriaIds)
                    ->count();

                if ($studentValueCount !== count($criteriaIds)) {
                    throw new \Exception(
                        "Siswa {$student->full_name} (NISN: {$student->nisn}) belum memiliki nilai lengkap untuk kriteria {$specialization}"
                    );
                }
            }

            // 5. Normalisasi nilai untuk setiap kriteria
            $normalizedValues = $this->normalizeValues($allValues, $weights);

            // 6. Hitung SAW score untuk setiap siswa
            $results = [];
            foreach ($students as $student) {
                $sawScore = $this->calculateStudentScore($student->id, $weights, $normalizedValues);
                
                $result = SawResult::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'academic_year_id' => $academicYearId,
                        'specialization' => $specialization,
                    ],
                    [
                        'final_score' => $sawScore['final_score'],
                        'detail_calculation' => $sawScore['details'],
                        'calculated_at' => now(),
                        'calculated_by' => $calculatedBy,
                    ]
                );

                $results[] = $result;
            }

            // 7. Update ranking
            $this->updateRankings($academicYearId, $specialization);

            return [
                'success' => true,
                'data' => [
                    'total_students' => count($results),
                    'results' => $results,
                ],
                'message' => "Perhitungan SAW berhasil untuk {$specialization}: " . count($results) . " siswa",
            ];

        } catch (\Exception $e) {
            Log::error("SAW Calculation Error ({$specialization}): " . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Normalisasi nilai berdasarkan tipe atribut (benefit/cost)
     */
    private function normalizeValues($allValues, $weights): array
    {
        $normalized = [];

        foreach ($weights as $weight) {
            $criteriaId = $weight->criteria_id;
            $attributeType = $weight->criteria->attribute_type;

            if (!isset($allValues[$criteriaId])) {
                continue;
            }

            $values = $allValues[$criteriaId];
            $rawValues = $values->pluck('raw_value')->toArray();

            if ($attributeType === 'benefit') {
                // Benefit: rij = xij / max(xij)
                $maxValue = max($rawValues);
                
                foreach ($values as $value) {
                    $normalizedValue = $maxValue > 0 ? $value->raw_value / $maxValue : 0;
                    
                    // Update ke database
                    $value->update(['normalized_value' => $normalizedValue]);
                    
                    $normalized[$criteriaId][$value->student_id] = $normalizedValue;
                }

            } else {
                // Cost: rij = min(xij) / xij
                $minValue = min($rawValues);
                
                foreach ($values as $value) {
                    $normalizedValue = $value->raw_value > 0 ? $minValue / $value->raw_value : 0;
                    
                    // Update ke database
                    $value->update(['normalized_value' => $normalizedValue]);
                    
                    $normalized[$criteriaId][$value->student_id] = $normalizedValue;
                }
            }
        }

        return $normalized;
    }

    /**
     * Hitung SAW score untuk satu siswa
     */
    private function calculateStudentScore(int $studentId, $weights, array $normalizedValues): array
    {
        $finalScore = 0;
        $details = [];

        foreach ($weights as $weight) {
            $criteriaId = $weight->criteria_id;
            $criteriaCode = $weight->criteria->code;
            $w = (float) $weight->weight;
            
            $r = $normalizedValues[$criteriaId][$studentId] ?? 0;
            $score = $w * $r;
            
            $finalScore += $score;

            $details[$criteriaCode] = [
                'criteria_name' => $weight->criteria->name,
                'weight' => $w,
                'normalized_value' => $r,
                'score' => $score,
            ];
        }

        return [
            'final_score' => round($finalScore, 8),
            'details' => $details,
        ];
    }

    /**
     * Update ranking berdasarkan final score
     */
    private function updateRankings(int $academicYearId, string $specialization): void
    {
        $results = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->orderBy('final_score', 'desc')
            ->get();

        $rank = 1;
        foreach ($results as $result) {
            $result->update(['rank' => $rank]);
            $rank++;
        }
    }

    /**
     * Get ranking untuk specialization tertentu
     */
    public function getRankings(int $academicYearId, string $specialization, int $limit = null): array
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
     * Get student score detail untuk semua spesialisasi
     */
    public function getStudentAllScores(int $studentId, int $academicYearId): array
    {
        $results = SawResult::with(['student', 'academicYear'])
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->keyBy('specialization');

        return [
            'tahfiz' => $results->get('tahfiz'),
            'language' => $results->get('language'),
        ];
    }

    /**
     * Get student score detail untuk satu spesialisasi
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
            'student' => $result->student,
            'academic_year' => $result->academicYear,
            'specialization' => $result->specialization,
            'final_score' => $result->final_score,
            'rank' => $result->rank,
            'detail_calculation' => $result->detail_calculation,
            'calculated_at' => $result->calculated_at,
        ];
    }
}