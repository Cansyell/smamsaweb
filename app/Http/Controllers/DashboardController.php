<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Statistics for active academic year only
        $stats = [
            'total_students' => Student::where('academic_year_id', $activeYear->id)->count(),
            'pending_validation' => Student::where('academic_year_id', $activeYear->id)
                                          ->where('validation_status', 'pending')->count(),
            'validated' => Student::where('academic_year_id', $activeYear->id)
                                 ->where('validation_status', 'valid')->count(),
            'rejected' => Student::where('academic_year_id', $activeYear->id)
                                ->where('validation_status', 'rejected')->count(),
            'tahfiz_quota' => $activeYear->tahfiz_quota ?? 0,
            'bahasa_quota' => $activeYear->bahasa_quota ?? 0,
            'tahfiz_filled' => Student::where('academic_year_id', $activeYear->id)
                                     ->where('specialization', 'tahfiz')
                                     ->where('validation_status', 'accepted')->count(),
            'bahasa_filled' => Student::where('academic_year_id', $activeYear->id)
                                     ->where('specialization', 'bahasa')
                                     ->where('validation_status', 'accepted')->count(),
        ];
        
        // Recent students from active year
        $recentStudents = Student::where('academic_year_id', $activeYear->id)
                                ->with('user')
                                ->latest()
                                ->limit(10)
                                ->get();
        
        return view('dashboard', [
            'page' => 'dashboard',
            'stats' => $stats,
            'recentStudents' => $recentStudents,
            'activeYear' => $activeYear
        ]);
    }
}