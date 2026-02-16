<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Document;
use App\Service\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResubmissionController extends Controller
{
    protected ValidationService $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Show the resubmission form (edit data + documents).
     * Only accessible when validation_status === 'invalid'.
     */
    public function show(Student $student)
    {
        $this->authorizeStudent($student);

        if ($student->validation_status !== 'invalid') {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'Data tidak bisa diperbaiki karena status bukan ditolak.');
        }

        $validationData = $this->validationService->getValidationDetails($student);

        return view('student.resubmission.show', [
            'student'        => $student,
            'validationData' => $validationData,
        ]);
    }

    /**
     * Update personal data during resubmission.
     */
    public function updatePersonalData(Request $request, Student $student)
    {
        $this->authorizeStudent($student);
        $this->ensureCanResubmit($student);

        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'nisn'            => 'required|string|max:20',
            'father_name'     => 'nullable|string|max:255',
            'mother_name'     => 'nullable|string|max:255',
            'gender'          => 'required|in:M,F',
            'place_of_birth'  => 'nullable|string|max:100',
            'date_of_birth'   => 'nullable|date',
            'address'         => 'nullable|string|max:500',
            'phone_number'    => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:2000|max:' . now()->year,
            'kip_number'      => 'nullable|string|max:50',
            'specialization'  => 'nullable|in:tahfiz,language,regular',
        ]);

        $student->update($validated);

        return redirect()
            ->route('student.resubmission.show', $student)
            ->with('success', 'Data pribadi berhasil diperbarui.');
    }

    /**
     * Update report grade during resubmission.
     */
    public function updateReportGrade(Request $request, Student $student)
    {
        $this->authorizeStudent($student);
        $this->ensureCanResubmit($student);

        $validated = $request->validate([
            'pai_grade'        => 'required|numeric|min:0|max:100',
            'indonesian_grade' => 'required|numeric|min:0|max:100',
            'english_grade'    => 'required|numeric|min:0|max:100',
        ]);

        $student->reportGrade()->updateOrCreate(
            ['student_id' => $student->id],
            $validated
        );

        return redirect()
            ->route('student.resubmission.show', $student)
            ->with('success', 'Nilai raport berhasil diperbarui.');
    }

    /**
     * Replace / upload a document during resubmission.
     */
    public function replaceDocument(Request $request, Student $student)
    {
        $this->authorizeStudent($student);
        $this->ensureCanResubmit($student);

        $request->validate([
            'document_type' => 'required|string',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $file     = $request->file('file');
        $path     = $file->store("documents/{$student->student_id}", 'public');
        $fileName = $file->getClientOriginalName();

        // Delete old document of same type if exists
        $old = $student->documents()->where('document_type', $request->document_type)->first();
        if ($old) {
            Storage::disk('public')->delete($old->file_path);
            $old->delete();
        }

        $student->documents()->create([
            'document_type'     => $request->document_type,
            'file_path'         => $path,
            'file_name'         => $fileName,
            'validation_status' => 'pending', // reset status after replacement
            'notes'             => null,
        ]);

        return redirect()
            ->route('student.resubmission.show', $student)
            ->with('success', 'Dokumen berhasil diunggah ulang.');
    }

    /**
     * Submit corrected data for re-validation by committee.
     */
    public function submit(Request $request, Student $student)
    {
        $this->authorizeStudent($student);

        $request->validate([
            'resubmission_notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->validationService->resubmitStudent($student, $request->resubmission_notes);

        if ($result['success']) {
            return redirect()
                ->route('student.dashboard')
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->with('error', $result['message']);
    }

    /* =====================================================================
     | HELPERS
     ===================================================================== */

    private function authorizeStudent(Student $student): void
    {
        abort_unless(
            $student->user_id === auth()->id(),
            403,
            'Anda tidak berhak mengakses data ini.'
        );
    }

    private function ensureCanResubmit(Student $student): void
    {
        abort_unless(
            $student->validation_status === 'invalid',
            403,
            'Data tidak dapat diubah. Hanya data dengan status ditolak yang dapat diperbaiki.'
        );
    }
}