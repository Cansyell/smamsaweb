<?php

namespace App\Service;

use App\Models\Student;
use App\Models\SawResult;
use App\Models\SpecializationQuota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingService
{
    /**
     * Tentukan status penerimaan untuk semua siswa dengan 3 jalur:
     * 1. Tahfiz Ranking (berdasarkan SAW Tahfiz) - untuk siswa yang pilih tahfiz/language
     * 2. Language Ranking (berdasarkan SAW Language) - untuk siswa yang pilih tahfiz/language & tidak lolos jalur 1
     * 3. Regular Ranking (FCFS berdasarkan validated_at) - untuk:
     *    - Siswa yang memilih 'regular' dari awal (langsung masuk sini)
     *    - Siswa yang pilih tahfiz/language tapi tidak lolos jalur 1 & 2
     *
     * @param int $academicYearId
     * @return array
     */
    public function determineAcceptanceStatus(int $academicYearId): array
    {
        try {
            DB::beginTransaction();

            // Get quota
            $quota = SpecializationQuota::getActiveByAcademicYear($academicYearId);
            
            if (!$quota) {
                throw new \Exception('Kuota belum diatur untuk tahun ajaran ini');
            }

            $tahfizQuota = $quota->tahfiz_quota;
            $languageQuota = $quota->language_quota;
            $regularQuota = $quota->regular_quota;

            // Get all valid students
            $allStudents = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->get();

            $acceptedTahfiz = [];
            $acceptedLanguage = [];
            $acceptedRegular = [];
            $rejected = [];

            // JALUR 1: Tahfiz Acceptance (berdasarkan ranking SAW Tahfiz)
            // HANYA untuk siswa yang pilih tahfiz atau language
            $tahfizResults = SawResult::where('academic_year_id', $academicYearId)
                ->where('specialization', 'tahfiz')
                ->orderBy('rank')
                ->get();

            foreach ($tahfizResults as $result) {
                if ($result->rank <= $tahfizQuota) {
                    $acceptedTahfiz[] = $result->student_id;
                    
                    // Update student
                    Student::where('id', $result->student_id)->update([
                        'final_class_type' => 'tahfiz',
                        'final_status' => 'accepted',
                        'ranking' => $result->rank,
                    ]);
                }
            }

            // JALUR 2: Language Acceptance (berdasarkan ranking SAW Language)
            // HANYA untuk siswa yang pilih tahfiz/language DAN TIDAK LOLOS Tahfiz
            $languageResults = SawResult::where('academic_year_id', $academicYearId)
                ->where('specialization', 'language')
                ->whereNotIn('student_id', $acceptedTahfiz) // Exclude yang sudah diterima di Tahfiz
                ->orderBy('rank')
                ->get();

            $languageRank = 1;
            foreach ($languageResults as $result) {
                if ($languageRank <= $languageQuota) {
                    $acceptedLanguage[] = $result->student_id;
                    
                    // Update student
                    Student::where('id', $result->student_id)->update([
                        'final_class_type' => 'language',
                        'final_status' => 'accepted',
                        'ranking' => $languageRank,
                    ]);
                    
                    $languageRank++;
                }
            }

            // JALUR 3: Regular Acceptance (FCFS berdasarkan validated_at)
            // Untuk:
            // A. Siswa yang memilih 'regular' dari awal (prioritas PERTAMA karena pilihan mereka)
            // B. Siswa yang pilih tahfiz/language tapi tidak lolos jalur 1 & 2
            $acceptedStudentIds = array_merge($acceptedTahfiz, $acceptedLanguage);
            
            // Ambil kandidat regular: semua siswa yang belum diterima
            $regularCandidates = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->whereNotIn('id', $acceptedStudentIds)
                ->orderByRaw("CASE WHEN specialization = 'regular' THEN 0 ELSE 1 END") // Regular choice FIRST
                ->orderBy('validated_at') // Then FCFS
                ->orderBy('created_at')   // Backup: registration time
                ->take($regularQuota)
                ->get();

            $regularRank = 1;
            foreach ($regularCandidates as $student) {
                $acceptedRegular[] = $student->id;
                
                $student->update([
                    'final_class_type' => 'regular',
                    'final_status' => 'accepted',
                    'ranking' => $regularRank,
                ]);
                
                $regularRank++;
            }

            // REJECTED: Siswa yang tidak lolos di semua jalur
            $allAccepted = array_merge($acceptedTahfiz, $acceptedLanguage, $acceptedRegular);
            
            $rejectedStudents = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->whereNotIn('id', $allAccepted)
                ->get();

            foreach ($rejectedStudents as $student) {
                $rejected[] = $student->id;
                
                $student->update([
                    'final_class_type' => null,
                    'final_status' => 'rejected',
                    'ranking' => null,
                ]);
            }

            DB::commit();

            // Count students by original choice
            $regularChoiceAccepted = Student::whereIn('id', $acceptedRegular)
                ->where('specialization', 'regular')
                ->count();
            
            $tahfizLanguageToRegular = count($acceptedRegular) - $regularChoiceAccepted;

            return [
                'success' => true,
                'data' => [
                    'tahfiz' => [
                        'quota' => $tahfizQuota,
                        'accepted' => count($acceptedTahfiz),
                        'students' => $acceptedTahfiz,
                    ],
                    'language' => [
                        'quota' => $languageQuota,
                        'accepted' => count($acceptedLanguage),
                        'students' => $acceptedLanguage,
                    ],
                    'regular' => [
                        'quota' => $regularQuota,
                        'accepted' => count($acceptedRegular),
                        'from_regular_choice' => $regularChoiceAccepted,
                        'from_tahfiz_language' => $tahfizLanguageToRegular,
                        'students' => $acceptedRegular,
                    ],
                    'rejected' => [
                        'total' => count($rejected),
                        'students' => $rejected,
                    ],
                ],
                'message' => 'Status penerimaan berhasil ditentukan',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Determine Acceptance Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal menentukan status penerimaan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get acceptance summary untuk dashboard
     */
    public function getAcceptanceSummary(int $academicYearId): array
    {
        $quota = SpecializationQuota::getActiveByAcademicYear($academicYearId);

        $summary = [
            'tahfiz' => [
                'quota' => $quota->tahfiz_quota ?? 0,
                'accepted' => Student::where('academic_year_id', $academicYearId)
                    ->where('final_class_type', 'tahfiz')
                    ->where('final_status', 'accepted')
                    ->count(),
            ],
            'language' => [
                'quota' => $quota->language_quota ?? 0,
                'accepted' => Student::where('academic_year_id', $academicYearId)
                    ->where('final_class_type', 'language')
                    ->where('final_status', 'accepted')
                    ->count(),
            ],
            'regular' => [
                'quota' => $quota->regular_quota ?? 0,
                'accepted' => Student::where('academic_year_id', $academicYearId)
                    ->where('final_class_type', 'regular')
                    ->where('final_status', 'accepted')
                    ->count(),
            ],
            'rejected' => Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->where('final_status', 'rejected')
                ->count(),
            'total_valid' => Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->count(),
        ];

        // Calculate percentages
        foreach (['tahfiz', 'language', 'regular'] as $type) {
            $summary[$type]['percentage'] = $summary[$type]['quota'] > 0
                ? round(($summary[$type]['accepted'] / $summary[$type]['quota']) * 100, 1)
                : 0;
            $summary[$type]['available'] = max(0, $summary[$type]['quota'] - $summary[$type]['accepted']);
        }

        return $summary;
    }

    /**
     * Get detailed student acceptance info
     */
    public function getStudentAcceptanceInfo(Student $student): array
    {
        $quota = SpecializationQuota::getActiveByAcademicYear($student->academic_year_id);

        // Get SAW results untuk kedua spesialisasi
        $tahfizResult = SawResult::where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', 'tahfiz')
            ->first();

        $languageResult = SawResult::where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', 'language')
            ->first();

        return [
            'student' => $student,
            'final_status' => $student->final_status,
            'final_class_type' => $student->final_class_type,
            'final_ranking' => $student->ranking,
            'tahfiz' => [
                'rank' => $tahfizResult->rank ?? null,
                'score' => $tahfizResult->final_score ?? null,
                'quota' => $quota->tahfiz_quota ?? 0,
                'is_eligible' => $tahfizResult && $tahfizResult->rank <= ($quota->tahfiz_quota ?? 0),
            ],
            'language' => [
                'rank' => $languageResult->rank ?? null,
                'score' => $languageResult->final_score ?? null,
                'quota' => $quota->language_quota ?? 0,
                'is_eligible' => $languageResult && $languageResult->rank <= ($quota->language_quota ?? 0),
            ],
            'validated_at' => $student->validated_at,
            'registered_at' => $student->created_at,
        ];
    }

    /**
     * Get ranking list untuk specific class type
     */
    public function getRankingList(int $academicYearId, string $classType, int $limit = null): array
    {
        $query = Student::where('academic_year_id', $academicYearId)
            ->where('final_class_type', $classType)
            ->where('final_status', 'accepted')
            ->with(['user', 'sawResult' => function($q) use ($classType) {
                // Untuk Regular, tidak perlu SAW result
                if ($classType !== 'regular') {
                    $q->where('specialization', $classType);
                }
            }])
            ->orderBy('ranking');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->map(function($student) {
            return [
                'student' => $student,
                'rank' => $student->ranking,
                'final_score' => $student->sawResult->final_score ?? null,
            ];
        })->toArray();
    }
}