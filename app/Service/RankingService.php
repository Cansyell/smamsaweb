<?php

namespace App\Service;

use App\Models\AcademicYear;
use App\Models\SawResult;
use App\Models\SpecializationQuota;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingService
{
    /**
     * Tentukan status penerimaan untuk SEMUA siswa.
     *
     * Logika:
     *  - Tahfiz  → siswa yg MEMILIH tahfiz bersaing di quota tahfiz berdasarkan SAW rank tahfiz
     *  - Bahasa  → siswa yg MEMILIH bahasa bersaing di quota bahasa berdasarkan SAW rank bahasa
     *  - Regular → FCFS (urutan validated_at) vs quota regular
     *
     *  CROSS-ACCEPTED:
     *  Jika quota tahfiz belum penuh setelah siswa tahfiz selesai, sisa slot bisa diisi
     *  oleh siswa bahasa dengan rank tahfiz terbaik (dan sebaliknya).
     *
     *  DUAL PASS:
     *  Siswa yang lulus di KEDUA spesialisasi (karena cross-accepted) mendapat saran
     *  pindah ke spesialisasi dengan skor SAW lebih tinggi.
     */
    public function determineAcceptanceStatus(int $academicYearId): array
    {
        try {
            $quota = SpecializationQuota::where('academic_year_id', $academicYearId)
                ->where('is_active', true)
                ->first();

            if (!$quota) {
                return ['success' => false, 'message' => 'Quota spesialisasi belum dikonfigurasi.'];
            }

            $tahfizQuota   = $quota->tahfiz_quota   ?? 0;
            $languageQuota = $quota->language_quota ?? 0;
            $regularQuota  = $quota->regular_quota  ?? 0;

            // Inisialisasi semua counter & slot
            $tahfizAccepted        = 0;
            $languageAccepted      = 0;
            $tahfizSlotRemaining   = $tahfizQuota;
            $languageSlotRemaining = $languageQuota;
            $dualPassCount         = 0;

            DB::beginTransaction();

            // --------------------------------------------------
            // RESET semua status agar tidak ada data stale
            // --------------------------------------------------
            Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->update([
                    'final_status'               => 'rejected',
                    'dual_pass'                  => false,
                    'recommended_specialization' => null,
                    'accepted_specialization'    => null,
                    'cross_accepted'             => false,
                ]);

            // --------------------------------------------------
            // A. TAHFIZ — hanya siswa yang MEMILIH tahfiz
            //    Ranking berdasarkan SAW tahfiz (primary_rank)
            // --------------------------------------------------
            $tahfizStudents = SawResult::where('academic_year_id', $academicYearId)
                ->where('specialization', 'tahfiz')
                ->whereHas('student', fn($q) => $q
                    ->where('validation_status', 'valid')
                    ->where('specialization', 'tahfiz')
                )
                ->with('student')
                ->orderBy('primary_rank')
                ->get();

            foreach ($tahfizStudents as $result) {
                $student    = $result->student;
                $isAccepted = $result->primary_rank !== null
                           && $result->primary_rank <= $tahfizQuota;

                if ($isAccepted) {
                    Student::where('id', $student->id)->update([
                        'final_status'               => 'accepted',
                        'dual_pass'                  => false,
                        'recommended_specialization' => null,
                        'accepted_specialization'    => 'tahfiz',
                        'cross_accepted'             => false,
                    ]);
                    $tahfizAccepted++;
                    $tahfizSlotRemaining--;
                }
            }

            // --------------------------------------------------
            // B. BAHASA — hanya siswa yang MEMILIH bahasa
            //    Ranking berdasarkan SAW bahasa (primary_rank)
            // --------------------------------------------------
            $languageStudents = SawResult::where('academic_year_id', $academicYearId)
                ->where('specialization', 'language')
                ->whereHas('student', fn($q) => $q
                    ->where('validation_status', 'valid')
                    ->where('specialization', 'language')
                )
                ->with('student')
                ->orderBy('primary_rank')
                ->get();

            foreach ($languageStudents as $result) {
                $student    = $result->student; 
                $isAccepted = $result->primary_rank !== null
                           && $result->primary_rank <= $languageQuota;

                if ($isAccepted) {
                    Student::where('id', $student->id)->update([
                        'final_status'               => 'accepted',
                        'dual_pass'                  => false,
                        'recommended_specialization' => null,
                        'accepted_specialization'    => 'language',
                        'cross_accepted'             => false,
                    ]);
                    $languageAccepted++;
                    $languageSlotRemaining--;
                }
            }

            // --------------------------------------------------
            // C. CROSS-ACCEPTED (opsional — isi sisa slot)
            // --------------------------------------------------

            // C1. Sisa slot tahfiz → cari siswa BAHASA yang lolos rank tahfiz
            if ($tahfizSlotRemaining > 0) {
                $crossForTahfiz = SawResult::where('academic_year_id', $academicYearId)
                    ->where('specialization', 'tahfiz')
                    ->whereHas('student', fn($q) => $q
                        ->where('validation_status', 'valid')
                        ->where('specialization', 'language')
                    )
                    ->with('student')
                    ->orderBy('rank')
                    ->get();

                foreach ($crossForTahfiz as $result) {
                    if ($tahfizSlotRemaining <= 0) {
                        break;
                    }

                    $student = $result->student->fresh();
                    $alreadyPassedLanguage = $student->final_status === 'accepted';

                    if ($alreadyPassedLanguage) {
                        // Dual pass: lulus bahasa (primary) DAN tahfiz (cross)
                        $tahfizScore    = $result->final_score;
                        $languageResult = SawResult::where('student_id', $student->id)
                            ->where('academic_year_id', $academicYearId)
                            ->where('specialization', 'language')
                            ->first();
                        $languageScore = $languageResult?->final_score ?? 0;
                        $recommended   = $tahfizScore >= $languageScore ? 'tahfiz' : 'language';

                        Student::where('id', $student->id)->update([
                            'final_status'               => 'accepted',
                            'dual_pass'                  => true,
                            'recommended_specialization' => $recommended,
                            'accepted_specialization'    => 'language',
                            'cross_accepted'             => true,
                        ]);
                        $dualPassCount++;
                        // Slot TIDAK berkurang — sudah terhitung di quota bahasa
                    } else {
                        // Belum lulus di mana pun → cross-accepted ke tahfiz
                        Student::where('id', $student->id)->update([
                            'final_status'               => 'accepted',
                            'dual_pass'                  => false,
                            'recommended_specialization' => null,
                            'accepted_specialization'    => 'tahfiz',
                            'cross_accepted'             => true,
                        ]);
                        $tahfizAccepted++;
                        $tahfizSlotRemaining--;
                    }
                }
            }

            // C2. Sisa slot bahasa → cari siswa TAHFIZ yang lolos rank bahasa
            if ($languageSlotRemaining > 0) {
                $crossForLanguage = SawResult::where('academic_year_id', $academicYearId)
                    ->where('specialization', 'language')
                    ->whereHas('student', fn($q) => $q
                        ->where('validation_status', 'valid')
                        ->where('specialization', 'tahfiz')
                    )
                    ->with('student')
                    ->orderBy('rank')
                    ->get();

                foreach ($crossForLanguage as $result) {
                    if ($languageSlotRemaining <= 0) {
                        break;
                    }

                    $student = $result->student->fresh();
                    $alreadyPassedTahfiz = $student->final_status === 'accepted';

                    if ($alreadyPassedTahfiz) {
                        // Dual pass: lulus tahfiz (primary) DAN bahasa (cross)
                        $languageScore = $result->final_score;
                        $tahfizResult  = SawResult::where('student_id', $student->id)
                            ->where('academic_year_id', $academicYearId)
                            ->where('specialization', 'tahfiz')
                            ->first();
                        $tahfizScore = $tahfizResult?->final_score ?? 0;
                        $recommended = $tahfizScore >= $languageScore ? 'tahfiz' : 'language';

                        Student::where('id', $student->id)->update([
                            'final_status'               => 'accepted',
                            'dual_pass'                  => true,
                            'recommended_specialization' => $recommended,
                            'accepted_specialization'    => 'tahfiz',
                            'cross_accepted'             => true,
                        ]);
                        $dualPassCount++;
                        // Slot TIDAK berkurang — sudah terhitung di quota tahfiz
                    } else {
                        // Belum lulus di mana pun → cross-accepted ke bahasa
                        Student::where('id', $student->id)->update([
                            'final_status'               => 'accepted',
                            'dual_pass'                  => false,
                            'recommended_specialization' => null,
                            'accepted_specialization'    => 'language',
                            'cross_accepted'             => true,
                        ]);
                        $languageAccepted++;
                        $languageSlotRemaining--;
                    }
                }
            }

            // --------------------------------------------------
            // D. REGULAR — berdasarkan FCFS (urutan validated_at)
            // --------------------------------------------------
            $regularStudents = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->where('specialization', 'regular')
                ->orderBy('validated_at', 'asc')
                ->get();

            $regularAccepted = 0;
            $regularRejected = 0;

            foreach ($regularStudents as $index => $student) {
                $isAccepted = ($index + 1) <= $regularQuota;

                $student->update([
                    'final_status'            => $isAccepted ? 'accepted' : 'rejected',
                    'accepted_specialization' => $isAccepted ? 'regular' : null,
                    'cross_accepted'          => false,
                    'dual_pass'               => false,
                ]);

                $isAccepted ? $regularAccepted++ : $regularRejected++;
            }

            DB::commit();

            $totalRejected = Student::where('academic_year_id', $academicYearId)
                ->where('validation_status', 'valid')
                ->where('final_status', 'rejected')
                ->count();

            Log::info('Acceptance determined', [
                'academic_year_id'  => $academicYearId,
                'tahfiz_accepted'   => $tahfizAccepted,
                'language_accepted' => $languageAccepted,
                'regular_accepted'  => $regularAccepted,
                'dual_pass_count'   => $dualPassCount,
                'total_rejected'    => $totalRejected,
            ]);

            return [
                'success' => true,
                'message' => 'Status penerimaan berhasil ditentukan.',
                'data'    => [
                    'tahfiz'    => ['accepted' => $tahfizAccepted,   'quota' => $tahfizQuota],
                    'language'  => ['accepted' => $languageAccepted, 'quota' => $languageQuota],
                    'regular'   => ['accepted' => $regularAccepted,  'quota' => $regularQuota],
                    'dual_pass' => ['total' => $dualPassCount],
                    'rejected'  => ['total' => $totalRejected],
                ],
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('RankingService::determineAcceptanceStatus error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Ambil ringkasan dual-pass untuk ditampilkan di dashboard panitia.
     */
    public function getDualPassSummary(int $academicYearId): array
    {
        $students = Student::with(['sawResults'])
            ->where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid')
            ->where('dual_pass', true)
            ->get();

        return $students->map(function (Student $student) use ($academicYearId) {
            $tahfizResult   = $student->sawResults->firstWhere('specialization', 'tahfiz');
            $languageResult = $student->sawResults->firstWhere('specialization', 'language');

            return [
                'student'                => $student,
                'tahfiz_score'           => $tahfizResult?->final_score,
                'tahfiz_rank'            => $tahfizResult?->rank,
                'language_score'         => $languageResult?->final_score,
                'language_rank'          => $languageResult?->rank,
                'recommended'            => $student->recommended_specialization,
                'chosen'                 => $student->specialization,
                'already_at_recommended' => $student->specialization === $student->recommended_specialization,
            ];
        })->toArray();
    }
}