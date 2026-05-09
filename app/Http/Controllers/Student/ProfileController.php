<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        $academicYears = AcademicYear::where('is_active', true)->get();

        // Check if student can edit data
        $canEdit = $student ? $student->canEditData() : ['can_edit' => true, 'reason' => null];

        return view('student.profile.index', [
            'page' => 'profile',
            'student' => $student,
            'academicYears' => $academicYears,
            'progress' => $this->calculateProgress($student),
            'canEdit' => $canEdit,
        ]);
    }

    public function store(StudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $student = Student::createStudent($data);

            DB::commit();

            return redirect()
                ->route('student.profile.index')
                ->with('success', 'Data pribadi berhasil disimpan! ID Siswa Anda: ' . $student->student_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(StudentRequest $request, Student $student)
    {
        if ($student->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        // Check if student can edit data
        $canEdit = $student->canEditData();
        if (!$canEdit['can_edit']) {
            return redirect()
                ->route('student.profile.index')
                ->with('error', $canEdit['reason']);
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();
            $student->updateStudent($data);

            DB::commit();

            return redirect()
                ->route('student.profile.index')
                ->with('success', 'Data pribadi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function calculateProgress(?Student $student): array
    {
        if (!$student) {
            return ['percentage' => 0, 'completed' => 0, 'total' => 4];
        }
        return $student->getRegistrationProgress();
    }
}