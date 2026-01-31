<?php

namespace App\Service;

use App\Models\Student;
use App\Models\Document;
use App\Models\ReportGrade;
use App\Models\TestScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidationService
{
    /**
     * Validasi lengkap data siswa (approve)
     * 
     * @param Student $student
     * @param int $validatedBy - ID user yang melakukan validasi
     * @param string|null $notes
     * @return array
     */
    public function approveStudent(Student $student, int $validatedBy, ?string $notes = null): array
    {
        try {
            DB::beginTransaction();

            // Cek apakah data siswa sudah lengkap
            $validation = $this->validateStudentCompleteness($student);
            
            if (!$validation['is_complete']) {
                return [
                    'success' => false,
                    'message' => 'Data siswa belum lengkap',
                    'errors' => $validation['missing_data']
                ];
            }

            // Update status validasi siswa
            $student->update([
                'validation_status' => 'valid',
                'validation_notes' => $notes ?? 'Data siswa telah divalidasi dan dinyatakan valid',
                'validated_at' => now(),
            ]);

            // Auto-approve semua dokumen yang masih pending
            $this->autoApproveDocuments($student);

            DB::commit();

            Log::info("Student approved", [
                'student_id' => $student->id,
                'validated_by' => $validatedBy,
                'notes' => $notes
            ]);

            return [
                'success' => true,
                'message' => 'Data siswa berhasil divalidasi',
                'student' => $student->fresh()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Failed to approve student", [
                'student_id' => $student->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memvalidasi data siswa: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tolak validasi data siswa (reject)
     * 
     * @param Student $student
     * @param int $validatedBy
     * @param string $notes - Alasan penolakan (wajib)
     * @return array
     */
    public function rejectStudent(Student $student, int $validatedBy, string $notes): array
    {
        try {
            DB::beginTransaction();

            if (empty($notes)) {
                return [
                    'success' => false,
                    'message' => 'Alasan penolakan harus diisi'
                ];
            }

            // Update status validasi siswa
            $student->update([
                'validation_status' => 'invalid',
                'validation_notes' => $notes,
                'validated_at' => now(),
            ]);

            DB::commit();

            Log::info("Student rejected", [
                'student_id' => $student->id,
                'validated_by' => $validatedBy,
                'notes' => $notes
            ]);

            return [
                'success' => true,
                'message' => 'Data siswa telah ditolak',
                'student' => $student->fresh()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Failed to reject student", [
                'student_id' => $student->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menolak data siswa: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validasi kelengkapan data siswa
     * 
     * @param Student $student
     * @return array
     */
    public function validateStudentCompleteness(Student $student): array
    {
        $missingData = [];
        $isComplete = true;

        // 1. Validasi Data Pribadi
        $personalDataCheck = $this->validatePersonalData($student);
        if (!$personalDataCheck['is_valid']) {
            $isComplete = false;
            $missingData['personal_data'] = $personalDataCheck['errors'];
        }

        // 2. Validasi Nilai Raport
        $reportGradeCheck = $this->validateReportGrade($student);
        if (!$reportGradeCheck['is_valid']) {
            $isComplete = false;
            $missingData['report_grade'] = $reportGradeCheck['errors'];
        }

        // 3. Validasi Dokumen
        $documentsCheck = $this->validateDocuments($student);
        if (!$documentsCheck['is_valid']) {
            $isComplete = false;
            $missingData['documents'] = $documentsCheck['errors'];
        }

        // 4. Validasi Spesialisasi
        if (empty($student->specialization)) {
            $isComplete = false;
            $missingData['specialization'] = ['Spesialisasi belum dipilih'];
        }

        return [
            'is_complete' => $isComplete,
            'missing_data' => $missingData,
            'completeness_percentage' => $this->calculateCompletenessPercentage($student)
        ];
    }

    /**
     * Validasi data pribadi siswa
     * 
     * @param Student $student
     * @return array
     */
    private function validatePersonalData(Student $student): array
    {
        $errors = [];
        $requiredFields = [
            'full_name' => 'Nama Lengkap',
            'nisn' => 'NISN',
            'father_name' => 'Nama Ayah',
            'mother_name' => 'Nama Ibu',
            'gender' => 'Jenis Kelamin',
            'place_of_birth' => 'Tempat Lahir',
            'date_of_birth' => 'Tanggal Lahir',
            'address' => 'Alamat',
            'phone_number' => 'Nomor Telepon',
            'previous_school' => 'Sekolah Asal',
            'graduation_year' => 'Tahun Lulus',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($student->$field)) {
                $errors[] = "{$label} belum diisi";
            }
        }

        // Validasi format NISN (10 digit)
        if (!empty($student->nisn) && strlen($student->nisn) != 10) {
            $errors[] = "NISN harus 10 digit";
        }

        // Validasi format nomor telepon
        if (!empty($student->phone_number) && !preg_match('/^[0-9]{10,15}$/', $student->phone_number)) {
            $errors[] = "Format nomor telepon tidak valid";
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validasi nilai raport siswa
     * 
     * @param Student $student
     * @return array
     */
    private function validateReportGrade(Student $student): array
    {
        $errors = [];

        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            $errors[] = "Nilai raport belum diinput";
            return [
                'is_valid' => false,
                'errors' => $errors
            ];
        }

        // Validasi setiap nilai harus ada dan valid (0-100)
        $grades = [
            'islamic_studies' => 'Nilai PAI',
            'indonesian_language' => 'Nilai Bahasa Indonesia',
            'english_language' => 'Nilai Bahasa Inggris',
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
            'errors' => $errors,
            'average' => $reportGrade->average_grade ?? 0
        ];
    }

    /**
     * Validasi dokumen siswa
     * 
     * @param Student $student
     * @return array
     */
    private function validateDocuments(Student $student): array
    {
        $errors = [];
        $documents = $student->documents;

        if ($documents->count() < 2) {
            $errors[] = "Minimal 2 dokumen harus diupload (Ijazah dan Raport)";
        }

        // Cek apakah ada dokumen ijazah
        $hasCertificate = $documents->where('document_type', 'certificate')->count() > 0;
        if (!$hasCertificate) {
            $errors[] = "Dokumen Ijazah belum diupload";
        }

        // Cek apakah ada dokumen raport
        $hasReport = $documents->where('document_type', 'report')->count() > 0;
        if (!$hasReport) {
            $errors[] = "Dokumen Raport belum diupload";
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'total_documents' => $documents->count()
        ];
    }

    /**
     * Validasi individual dokumen
     * 
     * @param Document $document
     * @param string $status - 'valid' atau 'invalid'
     * @param string|null $notes
     * @return array
     */
    public function validateDocument(Document $document, string $status, ?string $notes = null): array
    {
        try {
            if (!in_array($status, ['valid', 'invalid'])) {
                return [
                    'success' => false,
                    'message' => 'Status validasi tidak valid. Harus "valid" atau "invalid"'
                ];
            }

            if ($status === 'invalid' && empty($notes)) {
                return [
                    'success' => false,
                    'message' => 'Alasan penolakan dokumen harus diisi'
                ];
            }

            $document->update([
                'validation_status' => $status,
                'notes' => $notes
            ]);

            return [
                'success' => true,
                'message' => $status === 'valid' 
                    ? 'Dokumen berhasil divalidasi' 
                    : 'Dokumen ditolak',
                'document' => $document->fresh()
            ];

        } catch (\Exception $e) {
            Log::error("Failed to validate document", [
                'document_id' => $document->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memvalidasi dokumen: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Auto approve semua dokumen siswa
     * 
     * @param Student $student
     * @return void
     */
    private function autoApproveDocuments(Student $student): void
    {
        $student->documents()
            ->where('validation_status', 'pending')
            ->update([
                'validation_status' => 'valid',
                'notes' => 'Divalidasi otomatis bersama data siswa'
            ]);
    }

    /**
     * Hitung persentase kelengkapan data
     * 
     * @param Student $student
     * @return float
     */
    private function calculateCompletenessPercentage(Student $student): float
    {
        $totalChecks = 4; // Personal Data, Report Grade, Documents, Specialization
        $completedChecks = 0;

        if ($this->validatePersonalData($student)['is_valid']) {
            $completedChecks++;
        }

        if ($this->validateReportGrade($student)['is_valid']) {
            $completedChecks++;
        }

        if ($this->validateDocuments($student)['is_valid']) {
            $completedChecks++;
        }

        if (!empty($student->specialization)) {
            $completedChecks++;
        }

        return ($completedChecks / $totalChecks) * 100;
    }

    /**
     * Get detail lengkap untuk halaman validasi
     * 
     * @param Student $student
     * @return array
     */
    public function getValidationDetails(Student $student): array
    {
        return [
            'student' => $student,
            'personal_data' => $this->getPersonalDataDetails($student),
            'report_grade' => $this->getReportGradeDetails($student),
            'test_score' => $this->getTestScoreDetails($student),
            'documents' => $this->getDocumentsDetails($student),
            'validation_check' => $this->validateStudentCompleteness($student),
            'can_approve' => $this->canApprove($student),
        ];
    }

    /**
     * Get detail data pribadi untuk tampilan
     * 
     * @param Student $student
     * @return array
     */
    private function getPersonalDataDetails(Student $student): array
    {
        return [
            'student_id' => $student->student_id,
            'nisn' => $student->nisn,
            'full_name' => $student->full_name,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'gender' => $student->gender_label,
            'place_of_birth' => $student->place_of_birth,
            'date_of_birth' => $student->date_of_birth?->format('d M Y'),
            'age' => $student->age ?? 0,
            'address' => $student->address,
            'phone_number' => $student->phone_number,
            'previous_school' => $student->previous_school,
            'graduation_year' => $student->graduation_year,
            'kip_number' => $student->kip_number,
            'has_kip' => $student->has_kip,
            'specialization' => $student->specialization_label,
            'email' => $student->user->email ?? '-',
        ];
    }

    /**
     * Get detail nilai raport untuk tampilan
     * 
     * @param Student $student
     * @return array|null
     */
    private function getReportGradeDetails(Student $student): ?array
    {
        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            return null;
        }

        return [
            'islamic_studies' => $reportGrade->islamic_studies,
            'indonesian_language' => $reportGrade->indonesian_language,
            'english_language' => $reportGrade->english_language,
            'average_grade' => $reportGrade->average_grade,
        ];
    }

    /**
     * Get detail nilai tes untuk tampilan
     * 
     * @param Student $student
     * @return array|null
     */
    private function getTestScoreDetails(Student $student): ?array
    {
        $testScore = $student->testScore;

        if (!$testScore) {
            return null;
        }

        return [
            'quran_achievement' => $testScore->quran_achievement,
            'quran_reading' => $testScore->quran_reading,
            'interview' => $testScore->interview,
            'public_speaking' => $testScore->public_speaking,
            'dialogue' => $testScore->dialogue,
            'average_score' => $testScore->average_score,
            'grade' => $testScore->grade,
            'is_complete' => $testScore->isComplete(),
        ];
    }

    /**
     * Get detail dokumen untuk tampilan
     * 
     * @param Student $student
     * @return array
     */
    private function getDocumentsDetails(Student $student): array
    {
        return $student->documents->map(function ($doc) {
            return [
                'id' => $doc->id,
                'type' => $doc->document_type,
                'type_label' => $doc->type_label,
                'file_name' => $doc->file_name,
                'file_path' => $doc->file_path,
                'file_url' => $doc->file_url,
                'file_size' => $doc->file_size,
                'validation_status' => $doc->validation_status,
                'status_badge' => $doc->status_badge,
                'notes' => $doc->notes,
            ];
        })->toArray();
    }

    /**
     * Cek apakah siswa bisa di-approve
     * 
     * @param Student $student
     * @return bool
     */
    private function canApprove(Student $student): bool
    {
        $validation = $this->validateStudentCompleteness($student);
        return $validation['is_complete'];
    }

    /**
     * Batch approve multiple students
     * 
     * @param array $studentIds
     * @param int $validatedBy
     * @return array
     */
    public function batchApproveStudents(array $studentIds, int $validatedBy): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);
            
            if (!$student) {
                $results['failed'][] = [
                    'student_id' => $studentId,
                    'message' => 'Siswa tidak ditemukan'
                ];
                continue;
            }

            $result = $this->approveStudent($student, $validatedBy);
            
            if ($result['success']) {
                $results['success'][] = $student->full_name;
            } else {
                $results['failed'][] = [
                    'student_id' => $student->student_id,
                    'name' => $student->full_name,
                    'message' => $result['message']
                ];
            }
        }

        return $results;
    }

    /**
     * Get statistics for validation dashboard
     * 
     * @param int $academicYearId
     * @return array
     */
    public function getValidationStatistics(int $academicYearId): array
    {
        return [
            'total_students' => Student::byAcademicYear($academicYearId)->count(),
            'pending_validation' => Student::byAcademicYear($academicYearId)->pending()->count(),
            'validated' => Student::byAcademicYear($academicYearId)->valid()->count(),
            'rejected' => Student::byAcademicYear($academicYearId)->invalid()->count(),
            'need_test_scores' => Student::byAcademicYear($academicYearId)
                ->valid()
                ->whereDoesntHave('testScore')
                ->count(),
            'completed_tests' => Student::byAcademicYear($academicYearId)
                ->whereHas('testScore')
                ->count(),
        ];
    }
}