<?php

namespace App\Service;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\SawResult;
use App\Models\SpecializationQuota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecializationService
{
    /**
     * Get recommendation based on student grades
     * 
     * @param Student $student
     * @return array
     */
    public function getRecommendation(Student $student): array
    {
        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            return [
                'recommended' => null,
                'reason' => 'Data nilai belum lengkap',
                'confidence' => 0,
            ];
        }

        $paiGrade = $reportGrade->pai_grade ?? 0;
        $indonesianGrade = $reportGrade->indonesian_grade ?? 0;
        $englishGrade = $reportGrade->english_grade ?? 0;
        $averageGrade = $reportGrade->average_grade ?? 0;

        // Logika rekomendasi
        if ($paiGrade >= 85 && $averageGrade >= 80) {
            return [
                'recommended' => 'tahfiz',
                'reason' => 'Nilai PAI Anda sangat baik (' . $paiGrade . ') dan rata-rata nilai tinggi (' . $averageGrade . ')',
                'confidence' => 'high',
            ];
        } elseif ($englishGrade >= 85 && $indonesianGrade >= 80) {
            return [
                'recommended' => 'language',
                'reason' => 'Nilai Bahasa Inggris (' . $englishGrade . ') dan Bahasa Indonesia (' . $indonesianGrade . ') Anda sangat baik',
                'confidence' => 'high',
            ];
        } elseif ($averageGrade >= 75) {
            return [
                'recommended' => 'language',
                'reason' => 'Nilai rata-rata Anda baik (' . $averageGrade . '), cocok untuk kelas bahasa',
                'confidence' => 'medium',
            ];
        } else {
            return [
                'recommended' => 'regular',
                'reason' => 'Berdasarkan nilai Anda, kelas reguler dapat menjadi pilihan yang tepat',
                'confidence' => 'medium',
            ];
        }
    }

    /**
     * Get quota information for each specialization
     * 
     * @param int|null $academicYearId
     * @return array
     */
    public function getQuotaInformation(?int $academicYearId): array
    {
        if (!$academicYearId) {
            return [
                'tahfiz' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
                'language' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
                'regular' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
            ];
        }

        // Ambil quota information langsung dari SpecializationQuota model
        return SpecializationQuota::getQuotaInformation($academicYearId);
    }

    /**
     * Get statistics for a specialization
     * 
     * @param int $academicYearId
     * @param string $specialization
     * @return array
     */
    public function getSpecializationStatistics(int $academicYearId, string $specialization): array
    {
        $totalStudents = Student::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->where('validation_status', 'valid')
            ->count();

        $averageScore = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->avg('final_score');

        $highestScore = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->max('final_score');

        $lowestScore = SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->min('final_score');

        return [
            'total_students' => $totalStudents,
            'average_score' => $averageScore ? round($averageScore, 2) : 0,
            'highest_score' => $highestScore ? round($highestScore, 2) : 0,
            'lowest_score' => $lowestScore ? round($lowestScore, 2) : 0,
        ];
    }

    /**
     * Store student specialization choice
     * 
     * @param Student $student
     * @param string $specialization
     * @return array
     */
    public function storeSpecialization(Student $student, string $specialization): array
    {
        try {
            DB::beginTransaction();

            // Validasi quota
            if (in_array($specialization, ['tahfiz', 'language'])) {
                $quotaInfo = $this->getQuotaInformation($student->academic_year_id);
                
                if ($quotaInfo[$specialization]['available'] <= 0) {
                    return [
                        'success' => false,
                        'message' => 'Kuota untuk peminatan ' . ucfirst($specialization) . ' sudah penuh',
                    ];
                }
            }

            // Update specialization
            $student->update([
                'specialization' => $specialization,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pilihan peminatan berhasil disimpan! Silakan tunggu pengumuman hasil seleksi.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Specialization Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update student specialization choice
     * 
     * @param Student $student
     * @param string $specialization
     * @return array
     */
    public function updateSpecialization(Student $student, string $specialization): array
    {
        try {
            DB::beginTransaction();

            // Cek apakah masih bisa edit
            if ($student->testScore()->exists()) {
                return [
                    'success' => false,
                    'message' => 'Peminatan tidak dapat diubah setelah mengikuti tes interview.',
                ];
            }

            // Validasi quota
            if (in_array($specialization, ['tahfiz', 'language']) && $student->specialization !== $specialization) {
                $quotaInfo = $this->getQuotaInformation($student->academic_year_id);
                
                if ($quotaInfo[$specialization]['available'] <= 0) {
                    return [
                        'success' => false,
                        'message' => 'Kuota untuk peminatan ' . ucfirst($specialization) . ' sudah penuh',
                    ];
                }
            }

            $student->update([
                'specialization' => $specialization,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pilihan peminatan berhasil diperbarui!',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Specialization Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get student ranking information
     * 
     * @param Student $student
     * @return array|null
     */
    public function getStudentRanking(Student $student): ?array
    {
        if (empty($student->specialization)) {
            return null;
        }

        $ranking = SawResult::where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->first();

        if (!$ranking) {
            return null;
        }

        // Get quota info to determine if accepted
        $quotaInfo = $this->getQuotaInformation($student->academic_year_id);
        $quota = $quotaInfo[$student->specialization]['quota'] ?? 0;

        return [
            'rank' => $ranking->rank,
            'final_score' => $ranking->final_score,
            'total_students' => $quotaInfo[$student->specialization]['registered'] ?? 0,
            'is_accepted' => $ranking->rank <= $quota,
            'quota' => $quota,
            'detail_calculation' => $ranking->detail_calculation,
            'calculated_at' => $ranking->calculated_at,
        ];
    }

    /**
     * Check if student can choose specialization
     * 
     * @param Student $student
     * @return array
     */
    public function canChooseSpecialization(Student $student): array
    {
        $errors = [];

        if (!$student->isPersonalDataCompleted()) {
            $errors[] = 'Data pribadi belum lengkap';
        }

        if (!$student->isReportGradeCompleted()) {
            $errors[] = 'Nilai rapor belum lengkap';
        }

        if (!$student->isDocumentsCompleted()) {
            $errors[] = 'Dokumen belum lengkap (minimal 2 dokumen)';
        }

        return [
            'can_choose' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Check if student can edit specialization
     * 
     * @param Student $student
     * @return array
     */
    public function canEditSpecialization(Student $student): array
    {
        if (empty($student->specialization)) {
            return [
                'can_edit' => false,
                'reason' => 'Belum memilih peminatan',
            ];
        }

        if ($student->testScore()->exists()) {
            return [
                'can_edit' => false,
                'reason' => 'Peminatan tidak dapat diubah setelah mengikuti tes interview',
            ];
        }

        return [
            'can_edit' => true,
            'reason' => null,
        ];
    }
}