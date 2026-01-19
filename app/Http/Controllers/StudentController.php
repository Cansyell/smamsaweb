<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('user');

        // Filter by validation status
        if ($request->filled('status')) {
            $query->where('validation_status', $request->status);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->bySpecialization($request->specialization);
        }

        // Filter by graduation year
        if ($request->filled('graduation_year')) {
            $query->graduatedInYear($request->graduation_year);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            Student::createStudent($data);

            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil ditambahkan dan menunggu validasi.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('user');
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        try {
            $data = $request->validated();
            $student->updateStudent($data);

            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            $studentName = $student->full_name;
            $student->delete();

            return redirect()
                ->route('students.index')
                ->with('success', "Data siswa {$studentName} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validate student data
     */
    public function validateStudent(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:valid,invalid',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $student->validateStudent($request->status);

            $message = $request->status == 'valid' 
                ? "Data siswa {$student->full_name} berhasil divalidasi." 
                : "Data siswa {$student->full_name} ditandai sebagai tidak valid.";

            return redirect()
                ->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get pending students for validation
     */
    public function pending(Request $request)
    {
        $query = Student::pending()->with('user');

        // Search in pending students
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(10);

        return view('admin.students.pending', compact('students'));
    }

    /**
     * Bulk validate students
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|in:valid,invalid'
        ]);

        try {
            $count = Student::whereIn('id', $request->student_ids)
                ->update(['validation_status' => $request->status]);

            $statusText = $request->status == 'valid' ? 'divalidasi' : 'ditandai tidak valid';
            
            return redirect()
                ->back()
                ->with('success', "{$count} data siswa berhasil {$statusText}.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export students data (optional feature)
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality (Excel/PDF)
        // This is placeholder for future implementation
        
        return redirect()
            ->back()
            ->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total' => Student::count(),
            'pending' => Student::pending()->count(),
            'valid' => Student::valid()->count(),
            'invalid' => Student::invalid()->count(),
            'male' => Student::byGender('M')->count(),
            'female' => Student::byGender('F')->count(),
            'tahfiz' => Student::bySpecialization('tahfiz')->count(),
            'language' => Student::bySpecialization('language')->count(),
            'has_kip' => Student::whereNotNull('kip_number')->count(),
        ];

        return response()->json($stats);
    }
}