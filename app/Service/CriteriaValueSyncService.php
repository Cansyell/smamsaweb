<?php

namespace App\Service;

use App\Models\Student;
use App\Models\Criteria;
use App\Models\StudentCriterionValue;
use App\Models\ReportGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriteriaValueSyncService
{
    /**
     * Mapping antara kolom report_grades dan criteria code
     * Sesuaikan dengan criteria code yang ada di database
     */
    private const CRITERIA_MAPPING = [
        'islamic_studies' => 'C001',      // Code untuk kriteria Nilai Agama/PAI
        'english_language' => 'C002',     // Code untuk kriteria Nilai Bahasa Inggris
        'indonesian_language' => 'C003',  // Code untuk kriteria Nilai Bahasa Indonesia (jika ada)
    ];

    /**
     * Auto-sync nilai dari Report Grade ke Student Criterion Values
     * 
     * @param Student $student
     * @return array
     */
    public function syncReportGradeToValues(Student $student): array
    {
        try {
            DB::beginTransaction();

            $reportGrade = $student->reportGrade;

            if (!$reportGrade) {
                return [
                    'success' => false,
                    'message' => 'Report grade tidak ditemukan',
                ];
            }

            $synced = [];
            $skipped = [];

            foreach (self::CRITERIA_MAPPING as $gradeColumn => $criteriaCode) {
                // Get criteria by code
                $criteria = Criteria::where('code', $criteriaCode)
                    ->where('is_active', true)
                    ->first();

                if (!$criteria) {
                    $skipped[] = [
                        'column' => $gradeColumn,
                        'reason' => "Kriteria dengan code {$criteriaCode} tidak ditemukan atau tidak aktif",
                    ];
                    continue;
                }

                // Get value from report grade
                $value = $reportGrade->{$gradeColumn};

                if ($value === null || $value <= 0) {
                    $skipped[] = [
                        'column' => $gradeColumn,
                        'reason' => 'Nilai tidak valid atau kosong',
                    ];
                    continue;
                }

                // Sync to student_criterion_values
                StudentCriterionValue::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'criteria_id' => $criteria->id,
                    ],
                    [
                        'raw_value' => $value,
                        'notes' => 'Auto-synced from Report Grade',
                    ]
                );

                $synced[] = [
                    'criteria' => $criteria->name,
                    'code' => $criteriaCode,
                    'value' => $value,
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'data' => [
                    'synced' => $synced,
                    'skipped' => $skipped,
                    'total_synced' => count($synced),
                    'total_skipped' => count($skipped),
                ],
                'message' => count($synced) . ' nilai berhasil di-sync dari Report Grade',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync Report Grade Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal sync nilai: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Batch sync untuk semua siswa valid
     * 
     * @param int $academicYearId
     * @return array
     */
    public function batchSyncReportGrades(int $academicYearId): array
    {
        try {
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->whereHas('reportGrade')
                ->get();

            $successCount = 0;
            $failedCount = 0;
            $results = [];

            foreach ($students as $student) {
                $result = $this->syncReportGradeToValues($student);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }

                $results[] = [
                    'student' => $student->full_name,
                    'nisn' => $student->nisn,
                    'result' => $result,
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'total_students' => count($students),
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'details' => $results,
                ],
                'message' => "Batch sync selesai: {$successCount} berhasil, {$failedCount} gagal",
            ];

        } catch (\Exception $e) {
            Log::error('Batch Sync Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal batch sync: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check apakah nilai dari report grade sudah ter-sync
     * 
     * @param Student $student
     * @return array
     */
    public function checkSyncStatus(Student $student): array
    {
        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            return [
                'is_synced' => false,
                'message' => 'Report grade tidak ditemukan',
            ];
        }

        $status = [];

        foreach (self::CRITERIA_MAPPING as $gradeColumn => $criteriaCode) {
            $criteria = Criteria::where('code', $criteriaCode)
                ->where('is_active', true)
                ->first();

            if (!$criteria) {
                $status[$gradeColumn] = [
                    'synced' => false,
                    'reason' => 'Kriteria tidak ditemukan',
                ];
                continue;
            }

            $criterionValue = StudentCriterionValue::where('student_id', $student->id)
                ->where('criteria_id', $criteria->id)
                ->first();

            $reportGradeValue = $reportGrade->{$gradeColumn};

            if (!$criterionValue) {
                $status[$gradeColumn] = [
                    'synced' => false,
                    'reason' => 'Belum di-sync',
                ];
            } elseif ($criterionValue->raw_value != $reportGradeValue) {
                $status[$gradeColumn] = [
                    'synced' => false,
                    'reason' => 'Nilai tidak sama',
                    'report_grade_value' => $reportGradeValue,
                    'criterion_value' => $criterionValue->raw_value,
                ];
            } else {
                $status[$gradeColumn] = [
                    'synced' => true,
                    'value' => $reportGradeValue,
                ];
            }
        }

        $allSynced = collect($status)->every(fn($s) => $s['synced'] === true);

        return [
            'is_synced' => $allSynced,
            'details' => $status,
        ];
    }
}