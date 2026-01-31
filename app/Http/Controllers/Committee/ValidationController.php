<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Document;
use App\Models\AcademicYear;
use App\Service\ValidationService;
use App\Http\Requests\ValidateStudentRequest;
use App\Http\Requests\RejectStudentRequest;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    protected ValidationService $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Display a listing of students pending validation
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $query = Student::byAcademicYear($activeYear->id)
            ->with(['user', 'reportGrade', 'documents', 'testScore']);

        // Filter by validation status
        $status = $request->get('status', 'pending');
        if ($status === 'all') {
            // Show all students
        } elseif (in_array($status, ['pending', 'valid', 'invalid'])) {
            $query->where('validation_status', $status);
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        // Search by name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(20);

        // Get statistics
        $stats = $this->validationService->getValidationStatistics($activeYear->id);

        return view('committee.validation.index', [
            'page' => 'validation',
            'students' => $students,
            'stats' => $stats,
            'activeYear' => $activeYear,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Display the specified student for validation
     */
    public function show(Student $student)
    {
        // Get detailed validation information
        $validationData = $this->validationService->getValidationDetails($student);

        return view('committee.validation.show', [
            'page' => 'validation',
            'student' => $student,
            'validationData' => $validationData,
        ]);
    }

    /**
     * Approve student validation
     */
    public function approve(ValidateStudentRequest $request, Student $student)
    {
        $result = $this->validationService->approveStudent(
            $student,
            auth()->id(),
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->route('committee.validation.show', $student)
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    /**
     * Reject student validation
     */
    public function reject(RejectStudentRequest $request, Student $student)
    {
        $result = $this->validationService->rejectStudent(
            $student,
            auth()->id(),
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->route('committee.validation.show', $student)
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    /**
     * Validate individual document
     */
    public function validateDocument(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:valid,invalid',
            'notes' => 'required_if:status,invalid|nullable|string|max:500',
        ]);

        $result = $this->validationService->validateDocument(
            $document,
            $request->status,
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->back()
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    /**
     * Batch approve multiple students
     */
    public function batchApprove(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $results = $this->validationService->batchApproveStudents(
            $request->student_ids,
            auth()->id()
        );

        $successCount = count($results['success']);
        $failedCount = count($results['failed']);

        $message = "{$successCount} siswa berhasil divalidasi";
        
        if ($failedCount > 0) {
            $message .= ", {$failedCount} siswa gagal divalidasi";
        }

        return redirect()
            ->route('committee.validation.index')
            ->with('success', $message)
            ->with('batch_results', $results);
    }

    /**
     * Check student completeness via AJAX
     */
    public function checkCompleteness(Student $student)
    {
        $validation = $this->validationService->validateStudentCompleteness($student);

        return response()->json([
            'success' => true,
            'data' => $validation
        ]);
    }
}