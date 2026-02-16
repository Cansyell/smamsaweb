<?php

namespace App\Service;

use App\Models\Student;
use App\Models\Document;
use App\Models\ReportGrade;
use App\Models\TestScore;
use App\Models\ValidationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidationService
{
    /* =====================================================================
     | APPROVE
     ===================================================================== */

    /**
     * Validasi lengkap data siswa (approve)
     */
    public function approveStudent(Student $student, int $validatedBy, ?string $notes = null): array
    {
        try {
            DB::beginTransaction();

            $validation = $this->validateStudentCompleteness($student);

            if (!$validation['is_complete']) {
                return [
                    'success' => false,
                    'message' => 'Data siswa belum lengkap',
                    'errors'  => $validation['missing_data'],
                ];
            }

            $previousStatus = $student->validation_status;

            $student->update([
                'validation_status'        => 'valid',
                'validation_notes'         => $notes ?? 'Data siswa telah divalidasi dan dinyatakan valid',
                'validated_at'             => now(),
                'has_pending_resubmission' => false,
            ]);

            $this->autoApproveDocuments($student);

            $this->logAction($student, $validatedBy, 'approved', $previousStatus, 'valid', $notes);

            DB::commit();

            Log::info('Student approved', [
                'student_id'   => $student->id,
                'validated_by' => $validatedBy,
                'notes'        => $notes,
            ]);

            return [
                'success' => true,
                'message' => 'Data siswa berhasil divalidasi',
                'student' => $student->fresh(),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to approve student', [
                'student_id' => $student->id,
                'error'      => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memvalidasi data siswa: ' . $e->getMessage(),
            ];
        }
    }

    /* =====================================================================
     | REJECT
     ===================================================================== */

    /**
     * Tolak validasi data siswa (reject)
     */
    public function rejectStudent(Student $student, int $validatedBy, string $notes): array
    {
        try {
            DB::beginTransaction();

            if (empty($notes)) {
                return [
                    'success' => false,
                    'message' => 'Alasan penolakan harus diisi',
                ];
            }

            $previousStatus = $student->validation_status;

            $student->update([
                'validation_status'        => 'invalid',
                'validation_notes'         => $notes,
                'validated_at'             => now(),
                'has_pending_resubmission' => false,
            ]);

            $this->logAction($student, $validatedBy, 'rejected', $previousStatus, 'invalid', $notes);

            DB::commit();

            Log::info('Student rejected', [
                'student_id'   => $student->id,
                'validated_by' => $validatedBy,
                'notes'        => $notes,
            ]);

            return [
                'success' => true,
                'message' => 'Data siswa telah ditolak',
                'student' => $student->fresh(),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to reject student', [
                'student_id' => $student->id,
                'error'      => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menolak data siswa: ' . $e->getMessage(),
            ];
        }
    }

    /* =====================================================================
     | RESUBMIT (NEW)
     ===================================================================== */

    /**
     * Siswa mengajukan ulang data yang sudah diperbaiki.
     * Hanya bisa jika validation_status === 'invalid'.
     */
    public function resubmitStudent(Student $student, ?string $notes = null): array
    {
        if ($student->validation_status !== 'invalid') {
            return [
                'success' => false,
                'message' => 'Hanya data dengan status ditolak yang dapat diperbaiki.',
            ];
        }

        if ($student->has_pending_resubmission) {
            return [
                'success' => false,
                'message' => 'Sudah ada pengajuan ulang yang sedang menunggu review panitia.',
            ];
        }

        try {
            DB::beginTransaction();

            $newCount = ($student->resubmission_count ?? 0) + 1;

            $student->update([
                'validation_status'        => 'pending',
                'has_pending_resubmission' => true,
                'resubmission_count'       => $newCount,
                'resubmitted_at'           => now(),
                'resubmission_notes'       => $notes,
            ]);

            // Reset dokumen yang ditolak agar panitia mereview ulang
            $student->documents()
                ->where('validation_status', 'invalid')
                ->update([
                    'validation_status' => 'pending',
                    'notes'             => null,
                ]);

            $this->logAction(
                $student,
                $student->user_id,
                'resubmitted',
                'invalid',
                'pending',
                $notes,
                ['resubmission_count' => $newCount]
            );

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil diajukan kembali. Silakan tunggu konfirmasi dari panitia.',
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to resubmit student', [
                'student_id' => $student->id,
                'error'      => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengajukan ulang: ' . $e->getMessage(),
            ];
        }
    }

    /* =====================================================================
     | COMPLETENESS CHECK
     ===================================================================== */

    public function validateStudentCompleteness(Student $student): array
    {
        $missingData = [];
        $isComplete  = true;

        $personalDataCheck = $this->validatePersonalData($student);
        if (!$personalDataCheck['is_valid']) {
            $isComplete = false;
            $missingData['personal_data'] = $personalDataCheck['errors'];
        }

        $reportGradeCheck = $this->validateReportGrade($student);
        if (!$reportGradeCheck['is_valid']) {
            $isComplete = false;
            $missingData['report_grade'] = $reportGradeCheck['errors'];
        }

        $documentsCheck = $this->validateDocuments($student);
        if (!$documentsCheck['is_valid']) {
            $isComplete = false;
            $missingData['documents'] = $documentsCheck['errors'];
        }

        if (empty($student->specialization)) {
            $isComplete = false;
            $missingData['specialization'] = ['Spesialisasi belum dipilih'];
        }

        return [
            'is_complete'             => $isComplete,
            'missing_data'            => $missingData,
            'completeness_percentage' => $this->calculateCompletenessPercentage($student),
        ];
    }

    /* =====================================================================
     | PRIVATE VALIDATORS (unchanged from original)
     ===================================================================== */

    private function validatePersonalData(Student $student): array
    {
        $errors = [];
        $requiredFields = [
            'full_name'       => 'Nama Lengkap',
            'nisn'            => 'NISN',
            'father_name'     => 'Nama Ayah',
            'mother_name'     => 'Nama Ibu',
            'gender'          => 'Jenis Kelamin',
            'place_of_birth'  => 'Tempat Lahir',
            'date_of_birth'   => 'Tanggal Lahir',
            'address'         => 'Alamat',
            'phone_number'    => 'Nomor Telepon',
            'previous_school' => 'Sekolah Asal',
            'graduation_year' => 'Tahun Lulus',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($student->$field)) {
                $errors[] = "{$label} belum diisi";
            }
        }

        if (!empty($student->nisn) && strlen($student->nisn) != 10) {
            $errors[] = 'NISN harus 10 digit';
        }

        if (!empty($student->phone_number) && !preg_match('/^[0-9]{10,15}$/', $student->phone_number)) {
            $errors[] = 'Format nomor telepon tidak valid';
        }

        return ['is_valid' => empty($errors), 'errors' => $errors];
    }

    private function validateReportGrade(Student $student): array
    {
        $errors      = [];
        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            return ['is_valid' => false, 'errors' => ['Nilai raport belum diinput']];
        }

        // Nama field sesuai model asli
        $grades = [
            'islamic_studies'    => 'Nilai PAI',
            'indonesian_language'=> 'Nilai Bahasa Indonesia',
            'english_language'   => 'Nilai Bahasa Inggris',
        ];

        foreach ($grades as $field => $label) {
            $value = $reportGrade->$field;
            if (empty($value) || $value <= 0) {
                $errors[] = "{$label} belum diisi";
            } elseif ($value > 100) {
                $errors[] = "{$label} tidak boleh lebih dari 100";
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors'   => $errors,
            'average'  => $reportGrade->average_grade ?? 0,
        ];
    }

    private function validateDocuments(Student $student): array
    {
        $errors    = [];
        $documents = $student->documents;

        if ($documents->count() < 2) {
            $errors[] = 'Minimal 2 dokumen harus diupload (Ijazah dan Raport)';
        }

        if (!$documents->where('document_type', 'certificate')->count()) {
            $errors[] = 'Dokumen Ijazah belum diupload';
        }

        if (!$documents->where('document_type', 'report')->count()) {
            $errors[] = 'Dokumen Raport belum diupload';
        }

        return [
            'is_valid'        => empty($errors),
            'errors'          => $errors,
            'total_documents' => $documents->count(),
        ];
    }

    /* =====================================================================
     | DOCUMENT VALIDATION (unchanged from original)
     ===================================================================== */

    public function validateDocument(Document $document, string $status, ?string $notes = null): array
    {
        try {
            if (!in_array($status, ['valid', 'invalid'])) {
                return [
                    'success' => false,
                    'message' => 'Status validasi tidak valid. Harus "valid" atau "invalid"',
                ];
            }

            if ($status === 'invalid' && empty($notes)) {
                return [
                    'success' => false,
                    'message' => 'Alasan penolakan dokumen harus diisi',
                ];
            }

            $document->update([
                'validation_status' => $status,
                'notes'             => $notes,
            ]);

            $this->logAction(
                $document->student,
                auth()->id(),
                $status === 'valid' ? 'doc_validated' : 'doc_rejected',
                null,
                $status,
                $notes,
                ['document_id' => $document->id, 'document_type' => $document->document_type]
            );

            return [
                'success'  => true,
                'message'  => $status === 'valid' ? 'Dokumen berhasil divalidasi' : 'Dokumen ditolak',
                'document' => $document->fresh(),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to validate document', [
                'document_id' => $document->id,
                'error'       => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memvalidasi dokumen: ' . $e->getMessage(),
            ];
        }
    }

    /* =====================================================================
     | GET DETAILS (unchanged structure, added history & resubmission)
     ===================================================================== */

    public function getValidationDetails(Student $student): array
    {
        return [
            'student'          => $student,
            'personal_data'    => $this->getPersonalDataDetails($student),
            'report_grade'     => $this->getReportGradeDetails($student),
            'test_score'       => $this->getTestScoreDetails($student),
            'documents'        => $this->getDocumentsDetails($student),
            'validation_check' => $this->validateStudentCompleteness($student),
            'can_approve'      => $this->canApprove($student),
            'history'          => $this->getValidationHistory($student),      // NEW
            'resubmission'     => $this->getResubmissionInfo($student),       // NEW
        ];
    }

    private function getPersonalDataDetails(Student $student): array
    {
        return [
            'student_id'      => $student->student_id,
            'nisn'            => $student->nisn,
            'full_name'       => $student->full_name,
            'father_name'     => $student->father_name,
            'mother_name'     => $student->mother_name,
            'gender'          => $student->gender_label,
            'place_of_birth'  => $student->place_of_birth,
            'date_of_birth'   => $student->date_of_birth?->format('d M Y'),
            'age'             => $student->age ?? 0,
            'address'         => $student->address,
            'phone_number'    => $student->phone_number,
            'previous_school' => $student->previous_school,
            'graduation_year' => $student->graduation_year,
            'kip_number'      => $student->kip_number,
            'has_kip'         => $student->has_kip,
            'specialization'  => $student->specialization_label,
            'email'           => $student->user->email ?? '-',
        ];
    }

    private function getReportGradeDetails(Student $student): ?array
    {
        $reportGrade = $student->reportGrade;
        if (!$reportGrade) {
            return null;
        }

        return [
            'islamic_studies'    => $reportGrade->islamic_studies,
            'indonesian_language'=> $reportGrade->indonesian_language,
            'english_language'   => $reportGrade->english_language,
            'average_grade'      => $reportGrade->average_grade,
        ];
    }

    private function getTestScoreDetails(Student $student): ?array
    {
        $testScore = $student->testScore;
        if (!$testScore) {
            return null;
        }

        return [
            'quran_achievement' => $testScore->quran_achievement,
            'quran_reading'     => $testScore->quran_reading,
            'interview'         => $testScore->interview,
            'public_speaking'   => $testScore->public_speaking,
            'dialogue'          => $testScore->dialogue,
            'average_score'     => $testScore->average_score,
            'grade'             => $testScore->grade,
            'is_complete'       => $testScore->isComplete(),
        ];
    }

    private function getDocumentsDetails(Student $student): array
    {
        return $student->documents->map(function (Document $doc) {
            return [
                'id'               => $doc->id,
                'type'             => $doc->document_type,
                'type_label'       => $doc->type_label,
                'file_name'        => $doc->file_name,
                'file_path'        => $doc->file_path,
                'file_url'         => $doc->file_url,
                'file_size'        => $doc->file_size,
                'validation_status'=> $doc->validation_status,
                'status_badge'     => $doc->status_badge,
                'notes'            => $doc->notes,
            ];
        })->toArray();
    }

    /* =====================================================================
     | HISTORY & RESUBMISSION INFO (NEW)
     ===================================================================== */

    public function getValidationHistory(Student $student): array
    {
        try {
            return ValidationLog::where('student_id', $student->id)
                ->with('actor')
                ->latest()
                ->get()
                ->map(fn ($log) => [
                    'id'                 => $log->id,
                    'action'             => $log->action,
                    'action_label'       => $log->action_label,
                    'action_badge'       => $log->action_badge,
                    'actor_name'         => $log->actor?->name ?? 'Sistem',
                    'notes'              => $log->notes,
                    'previous_status'    => $log->previous_status,
                    'new_status'         => $log->new_status,
                    'resubmission_count' => $log->resubmission_count,
                    'created_at'         => $log->created_at->format('d M Y, H:i'),
                ])
                ->toArray();
        } catch (\Throwable $e) {
            // Tabel belum ada (sebelum migration dijalankan)
            return [];
        }
    }

    public function getResubmissionInfo(Student $student): array
    {
        return [
            'count'        => $student->resubmission_count ?? 0,
            'last_at'      => $student->resubmitted_at?->format('d M Y, H:i'),
            'notes'        => $student->resubmission_notes,
            'is_pending'   => (bool) ($student->has_pending_resubmission ?? false),
            'can_resubmit' => $student->validation_status === 'invalid'
                              && !($student->has_pending_resubmission ?? false),
        ];
    }

    /* =====================================================================
     | PRIVATE HELPERS
     ===================================================================== */

    private function canApprove(Student $student): bool
    {
        return $this->validateStudentCompleteness($student)['is_complete'];
    }

    private function autoApproveDocuments(Student $student): void
    {
        $student->documents()
            ->where('validation_status', 'pending')
            ->update([
                'validation_status' => 'valid',
                'notes'             => 'Divalidasi otomatis bersama data siswa',
            ]);
    }

    private function calculateCompletenessPercentage(Student $student): float
    {
        $totalChecks     = 4;
        $completedChecks = 0;

        if ($this->validatePersonalData($student)['is_valid'])  $completedChecks++;
        if ($this->validateReportGrade($student)['is_valid'])   $completedChecks++;
        if ($this->validateDocuments($student)['is_valid'])     $completedChecks++;
        if (!empty($student->specialization))                   $completedChecks++;

        return ($completedChecks / $totalChecks) * 100;
    }

    /**
     * Catat aksi ke tabel validation_logs.
     * Dibungkus try-catch agar tidak membatalkan transaksi utama
     * jika migration belum dijalankan.
     */
    private function logAction(
        Student $student,
        ?int    $actorId,
        string  $action,
        ?string $previousStatus,
        ?string $newStatus,
        ?string $notes    = null,
        array   $metadata = []
    ): void {
        try {
            ValidationLog::create([
                'student_id'         => $student->id,
                'actor_id'           => $actorId,
                'action'             => $action,
                'previous_status'    => $previousStatus,
                'new_status'         => $newStatus,
                'notes'              => $notes,
                'resubmission_count' => $student->resubmission_count ?? 0,
                'metadata'           => !empty($metadata) ? $metadata : null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('ValidationLog write failed', ['error' => $e->getMessage()]);
        }
    }

    /* =====================================================================
     | BATCH & STATISTICS (unchanged from original)
     ===================================================================== */

    public function batchApproveStudents(array $studentIds, int $validatedBy): array
    {
        $results = ['success' => [], 'failed' => []];

        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);

            if (!$student) {
                $results['failed'][] = [
                    'student_id' => $studentId,
                    'message'    => 'Siswa tidak ditemukan',
                ];
                continue;
            }

            $result = $this->approveStudent($student, $validatedBy);

            if ($result['success']) {
                $results['success'][] = $student->full_name;
            } else {
                $results['failed'][] = [
                    'student_id' => $student->student_id,
                    'name'       => $student->full_name,
                    'message'    => $result['message'],
                ];
            }
        }

        return $results;
    }

    public function getValidationStatistics(int $academicYearId): array
    {
        return [
            'total_students'     => Student::byAcademicYear($academicYearId)->count(),
            'pending_validation' => Student::byAcademicYear($academicYearId)->pending()->count(),
            'validated'          => Student::byAcademicYear($academicYearId)->valid()->count(),
            'rejected'           => Student::byAcademicYear($academicYearId)->invalid()->count(),
            'resubmitted'        => Student::byAcademicYear($academicYearId)          // NEW
                                        ->where('has_pending_resubmission', true)
                                        ->count(),
            'need_test_scores'   => Student::byAcademicYear($academicYearId)
                                        ->valid()
                                        ->whereDoesntHave('testScore')
                                        ->count(),
            'completed_tests'    => Student::byAcademicYear($academicYearId)
                                        ->whereHas('testScore')
                                        ->count(),
        ];
    }
}