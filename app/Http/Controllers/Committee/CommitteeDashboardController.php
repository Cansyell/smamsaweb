<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Announcement;
use Illuminate\Http\Request;

class CommitteeDashboardController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        $stats = [
            'pending_validation' => Student::byAcademicYear($activeYear->id)->pending()->count(),
            'need_test_scores'   => Student::byAcademicYear($activeYear->id)->valid()->whereDoesntHave('testScore')->count(),
            'completed_tests'    => Student::byAcademicYear($activeYear->id)->whereHas('testScore')->count(),
            'total_students'     => Student::byAcademicYear($activeYear->id)->count(),
        ];

        $pendingStudents = Student::byAcademicYear($activeYear->id)
            ->pending()
            ->with(['user', 'academicYear'])
            ->latest()
            ->limit(5)
            ->get();

        $needTestScores = Student::byAcademicYear($activeYear->id)
            ->valid()
            ->whereDoesntHave('testScore')
            ->with(['user', 'academicYear'])
            ->latest()
            ->limit(5)
            ->get();

        $announcements = Announcement::getLatestForDashboard(3);

        return view('committee.dashboard', [
            'page'            => 'dashboard',
            'stats'           => $stats,
            'pendingStudents' => $pendingStudents,
            'needTestScores'  => $needTestScores,
            'activeYear'      => $activeYear,
            'announcements'   => $announcements,
        ]);
    }
}