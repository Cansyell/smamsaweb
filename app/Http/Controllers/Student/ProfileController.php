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

        return view('student.profile.index', [
            'page' => 'profile',
            'student' => $student,
            'academicYears' => $academicYears,
            'progress' => $this->calculateProgress($student),
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