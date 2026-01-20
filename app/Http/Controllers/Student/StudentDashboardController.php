<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Get only current user's student data
        $student = Student::where('user_id', auth()->id())->first();
        
        if (!$student) {
            // If no student record, redirect to create profile
            return redirect()->route('student.profile')->with('info', 'Silakan lengkapi data pribadi Anda terlebih dahulu.');
        }
        
        // Calculate registration progress
        $progress = $this->calculateProgress($student);
        
        // Get registration steps status
        $steps = [
            [
                'name' => 'Data Pribadi',
                'completed' => $student->personal_data_completed ?? false,
                'route' => 'student.profile',
                'icon' => 'user',
                'description' => 'Lengkapi data pribadi Anda'
            ],
            [
                'name' => 'Nilai Rapor',
                'completed' => $student->grades_completed ?? false,
                'route' => 'student.grades',
                'icon' => 'document',
                'description' => 'Input nilai PAI, Bahasa Indonesia, dan Bahasa Inggris'
            ],
            [
                'name' => 'Upload Berkas',
                'completed' => $student->documents_completed ?? false,
                'route' => 'student.documents',
                'icon' => 'upload',
                'description' => 'Upload ijazah dan rapor SMP/MTs'
            ],
            [
                'name' => 'Pilih Peminatan',
                'completed' => $student->specialization_completed ?? false,
                'route' => 'student.specialization',
                'icon' => 'clipboard',
                'description' => 'Pilih peminatan Tahfiz atau Bahasa'
            ],
        ];
        
        // Validation status from panitia
        $validationStatus = [
            'status' => $student->validation_status ?? 'pending',
            'notes' => $student->validation_notes ?? null,
            'validated_at' => $student->validated_at ?? null,
        ];
        
        // Test scores status
        $testScoresStatus = [
            'completed' => $student->test_scores_completed ?? false,
            'quran_score' => $student->quran_score ?? null,
            'interview_score' => $student->interview_score ?? null,
            'speaking_score' => $student->speaking_score ?? null,
            'dialog_score' => $student->dialog_score ?? null,
        ];
        
        // Final result (if available)
        $finalResult = [
            'calculated' => $student->final_score !== null,
            'final_score' => $student->final_score ?? null,
            'ranking' => $student->ranking ?? null,
            'class_type' => $student->final_class_type ?? null, // tahfiz, bahasa, reguler
            'status' => $student->final_status ?? null, // accepted, waiting_list, rejected
        ];
        
        return view('student.dashboard', [
            'page' => 'dashboard',
            'student' => $student,
            'progress' => $progress,
            'steps' => $steps,
            'validationStatus' => $validationStatus,
            'testScoresStatus' => $testScoresStatus,
            'finalResult' => $finalResult,
        ]);
    }
    
    /**
     * Calculate registration progress percentage
     */
    private function calculateProgress($student)
    {
        $totalSteps = 4;
        $completedSteps = 0;
        
        if ($student->personal_data_completed) $completedSteps++;
        if ($student->grades_completed) $completedSteps++;
        if ($student->documents_completed) $completedSteps++;
        if ($student->specialization_completed) $completedSteps++;
        
        return [
            'percentage' => ($completedSteps / $totalSteps) * 100,
            'completed' => $completedSteps,
            'total' => $totalSteps,
        ];
    }
}
