<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ReportGrade;
use App\Models\Student;
use App\Service\DocumentService;
use App\Service\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResubmissionController extends Controller
{
    public function __construct(
        protected ValidationService $validationService,
        protected DocumentService   $documentService,
    ) {}

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------

    public function show(): \Illuminate\View\View
    {
        $student = $this->getStudent();

        $this->requireInvalidStatus($student);

        return view('student.resubmission.show', [
            'student'        => $student,
            'validationData' => $this->validationService->getValidationDetails($student),
            'documentLimits' => [
                'certificate' => $this->documentService->canUploadDocument('certificate'),
                'report'      => $this->documentService->canUploadDocument('report'),
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // UPDATE REPORT GRADE
    // -------------------------------------------------------------------------

    public function updateReportGrade(Request $request): RedirectResponse
    {
        $student = $this->getStudent();

        $this->requireInvalidStatus($student);

        $validated = $request->validate([
            'islamic_studies'     => ['required', 'numeric', 'min:0', 'max:100'],
            'indonesian_language' => ['required', 'numeric', 'min:0', 'max:100'],
            'english_language'    => ['required', 'numeric', 'min:0', 'max:100'],
            'ppkn'                => ['nullable', 'numeric', 'min:0', 'max:100'],
            'mtk'                 => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ipa'                 => ['nullable', 'numeric', 'min:0', 'max:100'],
            'seni_budaya'         => ['nullable', 'numeric', 'min:0', 'max:100'],
            'penjas'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'prakarya'            => ['nullable', 'numeric', 'min:0', 'max:100'],
        ], [
            'islamic_studies.required'     => 'Nilai PAI wajib diisi.',
            'indonesian_language.required' => 'Nilai Bahasa Indonesia wajib diisi.',
            'english_language.required'    => 'Nilai Bahasa Inggris wajib diisi.',
            '*.numeric'                    => ':attribute harus berupa angka.',
            '*.min'                        => ':attribute minimal 0.',
            '*.max'                        => ':attribute maksimal 100.',
        ]);

        try {
            $rg = $student->reportGrade;

            if ($rg) {
                $rg->updateGrade($validated);
            } else {
                ReportGrade::createGrade(array_merge($validated, ['student_id' => $student->id]));
            }

            return redirect()
                ->route('student.resubmission.show')
                ->with('success', 'Nilai raport berhasil diperbarui.');

        } catch (\Exception $e) {
            $this->logError('updateReportGrade', $e, $student->id);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui nilai raport: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // UPLOAD DOCUMENT
    // -------------------------------------------------------------------------

    public function uploadDocument(Request $request): RedirectResponse
    {
        $student = $this->getStudent();

        $this->requireInvalidStatus($student);

        $request->validate([
            'document_type' => ['required', 'string', 'in:certificate,report'],
            'file'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'document_type.required' => 'Jenis dokumen wajib dipilih.',
            'document_type.in'       => 'Jenis dokumen tidak valid.',
            'file.required'          => 'File wajib diunggah.',
            'file.mimes'             => 'Format file harus PDF, JPG, atau PNG.',
            'file.max'               => 'Ukuran file maksimal 5MB.',
        ]);

        $uploadCheck = $this->documentService->canUploadDocument($request->document_type);

        if (!$uploadCheck['can_upload']) {
            $label = $request->document_type === 'certificate' ? 'Ijazah' : 'Raport';
            return redirect()->back()
                ->with('error', "Batas maksimal upload {$label} adalah {$uploadCheck['limit']} file. Anda sudah mengunggah {$uploadCheck['current_count']} file.");
        }

        try {
            $this->documentService->createDocument(
                ['document_type' => $request->document_type],
                $request->file('file')
            );

            return redirect()
                ->route('student.resubmission.show')
                ->with('success', 'Dokumen berhasil diunggah.');

        } catch (\Exception $e) {
            $this->logError('uploadDocument', $e, $student->id);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunggah dokumen: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // SUBMIT
    // -------------------------------------------------------------------------

    public function submit(Request $request): RedirectResponse
    {
        $student = $this->getStudent();

        $this->requireInvalidStatus($student);

        $request->validate([
            'resubmission_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $result = $this->validationService->resubmitStudent(
                $student,
                $request->resubmission_notes
            );

            if ($result['success']) {
                return redirect()
                    ->route('student.dashboard')
                    ->with('success', $result['message']);
            }

            return redirect()->back()
                ->with('error', $result['message']);

        } catch (\Exception $e) {
            $this->logError('submit', $e, $student->id);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengajukan ulang: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function getStudent(): Student
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        return $student;
    }

    private function requireInvalidStatus(Student $student): void
    {
        abort_unless(
            $student->validation_status === 'invalid',
            403,
            'Data tidak dapat diubah. Hanya data dengan status ditolak yang dapat diperbaiki.'
        );
    }

    private function logError(string $method, \Exception $e, int $studentId): void
    {
        Log::error("ResubmissionController::{$method}: {$e->getMessage()}", [
            'student_id' => $studentId,
            'file'       => $e->getFile(),
            'line'       => $e->getLine(),
        ]);
    }
}