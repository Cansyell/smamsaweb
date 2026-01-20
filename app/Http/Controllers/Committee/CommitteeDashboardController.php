<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CommitteeDashboardController extends Controller
{
    public function index()
    {
        // Menggunakan helper method dari AcademicYear model
        $activeYear = AcademicYear::getActiveYear();
        
        // Jika tidak ada tahun ajaran aktif, redirect atau tampilkan pesan
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }
        
        // Menggunakan scope yang sudah dibuat di Student model
        $stats = [
            'pending_validation' => Student::byAcademicYear($activeYear->id)
                                          ->pending()
                                          ->count(),
            'need_test_scores' => Student::byAcademicYear($activeYear->id)
                                        ->valid()
                                        ->whereDoesntHave('testScore')
                                        ->count(),
            'completed_tests' => Student::byAcademicYear($activeYear->id)
                                       ->whereHas('testScore')
                                       ->count(),
            'total_students' => Student::byAcademicYear($activeYear->id)->count(),
        ];
        
        // Students pending validation dengan relasi user dan academicYear
        $pendingStudents = Student::byAcademicYear($activeYear->id)
                                 ->pending()
                                 ->with(['user', 'academicYear'])
                                 ->latest()
                                 ->limit(5)
                                 ->get();
        
        // Students need test scores dengan relasi user dan academicYear
        $needTestScores = Student::byAcademicYear($activeYear->id)
                                ->valid()
                                ->whereDoesntHave('testScore')
                                ->with(['user', 'academicYear'])
                                ->latest()
                                ->limit(5)
                                ->get();
        
        return view('committee.dashboard', [
            'page' => 'dashboard',
            'stats' => $stats,
            'pendingStudents' => $pendingStudents,
            'needTestScores' => $needTestScores,
            'activeYear' => $activeYear
        ]);
    }
}