<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Student::with([
            'reportGrade',
            'documents',
            'testScore',
            'finalScore',
            'academicYear'
        ])->where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('info', 'Silakan lengkapi data pribadi Anda terlebih dahulu.');
        }

        return view('student.dashboard', [
            'page' => 'dashboard',
            'student' => $student,
            'progress' => $student->getRegistrationProgress(),
            'steps' => [
                [
                    'name' => 'Data Pribadi',
                    'completed' => $student->isPersonalDataCompleted(),
                    'route' => 'student.profile.index',
                    'description' => 'Lengkapi data pribadi dan informasi orang tua',
                    'details' => $student->getPersonalDataDetails(),
                ],
                [
                    'name' => 'Nilai Rapor',
                    'completed' => $student->isReportGradeCompleted(),
                    'route' => 'student.report-grades.index',
                    'description' => 'Input nilai PAI, Bahasa Indonesia, dan Bahasa Inggris',
                    'details' => $student->getGradesDetails(),
                ],
                [
                    'name' => 'Upload Berkas',
                    'completed' => $student->isDocumentsCompleted(),
                    'route' => 'student.documents.index',
                    'description' => 'Upload ijazah dan rapor SMP/MTs',
                    'details' => $student->getDocumentsDetails(),
                ],
                [
                    'name' => 'Pilih Peminatan',
                    'completed' => $student->isSpecializationCompleted(),
                    'route' => 'student.specialization.index',
                    'description' => 'Pilih peminatan Tahfiz atau Bahasa',
                    'details' => $student->getSpecializationDetails(),
                ],
            ],
            'validationStatus' => $student->getValidationStatus(),
            'testScoresStatus' => $student->getTestScoresStatus(),
            'finalResult' => $student->getFinalResult(),
        ]);
    }
}