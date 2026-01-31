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
     * Hitung SAW score untuk semua siswa dalam specialization tertentu
     *
     * @param int $academicYearId
     * @param string $specialization
     * @param int|null $calculatedBy
     * @return array
     */
    public function calculateScores(int $academicYearId, string $specialization, ?int $calculatedBy = null): array
    {
        try {
            DB::beginTransaction();

            // 1. Ambil bobot kriteria dari AHP
            $weights = CriterionWeight::with('criteria')
                ->forAcademicYearAndSpecialization($academicYearId, $specialization)
                ->consistent()
                ->get();

            if ($weights->isEmpty()) {
                throw new \Exception("Bobot kriteria belum dihitung atau tidak konsisten untuk {$specialization}");
            }

            // 2. Ambil semua siswa yang mendaftar di specialization ini
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('specialization', $specialization)
                ->where('validation_status', 'valid')
                ->get();

            if ($students->isEmpty()) {
                throw new \Exception("Tidak ada siswa yang valid untuk spesializasi {$specialization}");
            }

            // 3. Ambil semua nilai siswa untuk kriteria ini
            $criteriaIds = $weights->pluck('criteria_id')->toArray();
            $studentIds = $students->pluck('id')->toArray();

            $allValues = StudentCriterionValue::whereIn('student_id', $studentIds)
                ->whereIn('criteria_id', $criteriaIds)
                ->get()
                ->groupBy('criteria_id');

            // 4. Normalisasi nilai untuk setiap kriteria
            $normalizedValues = $this->normalizeValues($allValues, $weights);

            // 5. Hitung SAW score untuk setiap siswa
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

            // 6. Update ranking
            $this->updateRankings($academicYearId, $specialization);

            DB::commit();

            return [
                'success' => true,
                'data' => [
                    'total_students' => count($results),
                    'results' => $results,
                ],
                'message' => "Perhitungan SAW berhasil untuk " . count($results) . " siswa",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SAW Calculation Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal menghitung SAW: ' . $e->getMessage(),
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
        $results = SawResult::forAcademicYearAndSpecialization($academicYearId, $specialization)
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
            ->forAcademicYearAndSpecialization($academicYearId, $specialization)
            ->ranked();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->toArray();
    }

    /**
     * Get student score detail
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
