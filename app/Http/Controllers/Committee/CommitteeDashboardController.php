<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Student;

class CommitteeDashboardController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        $base = fn() => Student::byAcademicYear($activeYear->id);

        $stats = [
            'total_students'     => $base()->count(),
            'pending_validation' => $base()->pending()->count(),
            'need_test_scores'   => $base()->valid()->whereDoesntHave('criterionValues')->count(),
            'completed_tests'    => $base()->valid()->whereHas('criterionValues')->count(),
        ];

        $pendingStudents = $base()
            ->pending()
            ->with(['user', 'academicYear'])
            ->latest()
            ->limit(5)
            ->get();

        $needTestScores = $base()
            ->valid()
            ->whereDoesntHave('criterionValues')
            ->where('specialization','!=','regular')
            ->with(['user', 'academicYear'])
            ->latest()
            ->limit(5)
            ->get();

        $announcements = Announcement::getLatestForDashboard(3);

        return view('committee.dashboard', [
            'page'            => 'dashboard',
            'activeYear'      => $activeYear,
            'stats'           => $stats,
            'pendingStudents' => $pendingStudents,
            'needTestScores'  => $needTestScores,
            'announcements'   => $announcements,
        ]);
    }
}