<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SawResult;
use App\Service\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SawResultController extends Controller
{
    public function __construct(protected RankingService $rankingService) {}

    // -----------------------------------------------------------------------
    // INDEX
    // -----------------------------------------------------------------------

    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $allStudents     = $this->getAllStudentsData($activeYear->id);
        $tahfizRanking   = $this->getTahfizRanking($activeYear->id);
        $languageRanking = $this->getLanguageRanking($activeYear->id);
        $regularRanking  = $this->getRegularRanking($activeYear->id);
        $dualPassList    = $this->rankingService->getDualPassSummary($activeYear->id);

        $stats = $this->buildStats($activeYear->id);

        return view('committee.saw-results.index', compact(
            'allStudents',
            'tahfizRanking',
            'languageRanking',
            'regularRanking',
            'dualPassList',
            'activeYear',
            'stats'
        ));
    }

    // -----------------------------------------------------------------------
    // SHOW
    // -----------------------------------------------------------------------

    public function show(Student $student)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $tahfizResult = SawResult::where([
            'student_id'       => $student->id,
            'academic_year_id' => $activeYear->id,
            'specialization'   => 'tahfiz',
        ])->first();

        $languageResult = SawResult::where([
            'student_id'       => $student->id,
            'academic_year_id' => $activeYear->id,
            'specialization'   => 'language',
        ])->first();

        $totalTahfizStudents = SawResult::where('academic_year_id', $activeYear->id)
            ->where('specialization', 'tahfiz')->count();

        $totalLanguageStudents = SawResult::where('academic_year_id', $activeYear->id)
            ->where('specialization', 'language')->count();

        return view('committee.saw-results.show', compact(
            'student',
            'tahfizResult',
            'languageResult',
            'totalTahfizStudents',
            'totalLanguageStudents'
        ));
    }

    // -----------------------------------------------------------------------
    // PRIVATE HELPERS
    // -----------------------------------------------------------------------

    /**
     * Data global — semua siswa dengan final_status aktual.
     * Badge ★ Dual dan ↔ Cross HANYA ditampilkan di sini.
     */
    private function getAllStudentsData(int $academicYearId): Collection
    {
        $students = Student::with('user')
            ->where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid')
            ->orderBy('full_name')
            ->get();

        $sawResults = SawResult::where('academic_year_id', $academicYearId)
            ->get()
            ->groupBy(fn($r) => $r->student_id . '-' . $r->specialization);

        return $students->map(function (Student $student) use ($sawResults) {
            $tahfizResult   = $sawResults->get($student->id . '-tahfiz')?->first();
            $languageResult = $sawResults->get($student->id . '-language')?->first();

            $finalStatus = $student->final_status ?? 'pending';
            $config      = $this->getProgramConfig($student->specialization);

            return [
                'student'       => $student,
                'final_status'  => $finalStatus,
                'status_in_tab' => $finalStatus,
                'validated_at'  => $student->validated_at,

                'program_label'       => $config['label'],
                'program_badge_color' => $config['badge_color'],
                'program_icon'        => $config['icon'],
                'avatar_color'        => $config['avatar_color'],

                // Rank & score hanya tampil jika siswa accepted
                'tahfiz_rank'  => ($finalStatus === 'accepted' && $tahfizResult)  ? $tahfizResult->rank        : null,
                'tahfiz_score' => ($finalStatus === 'accepted' && $tahfizResult)  ? $tahfizResult->final_score : null,

                'language_rank'  => ($finalStatus === 'accepted' && $languageResult) ? $languageResult->rank        : null,
                'language_score' => ($finalStatus === 'accepted' && $languageResult) ? $languageResult->final_score : null,

                // Badge dual/cross ditampilkan di tab global
                'dual_pass'                  => (bool) $student->dual_pass,
                'cross_accepted'             => (bool) $student->cross_accepted,
                'recommended_specialization' => $student->recommended_specialization,
                'accepted_specialization'    => $student->accepted_specialization,
            ];
        });
    }

    /**
     * Ranking Tahfiz — HANYA siswa yang memilih tahfiz.
     * Tidak ada badge cross/dual di tab ini.
     * Status lulus = final_status accepted (karena mereka bersaing di quota tahfiz sendiri).
     */
    private function getTahfizRanking(int $academicYearId): Collection
    {
        return SawResult::with(['student', 'student.user'])
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', 'tahfiz')
            ->whereHas('student', fn($q) => $q
                ->where('validation_status', 'valid')
                ->where('specialization', 'tahfiz') // hanya siswa yang memilih tahfiz
            )
            ->orderBy('rank')
            ->get()
            ->map(function (SawResult $result) {
                $student = $result->student;

                return [
                    'rank'          => $result->rank,
                    'student'       => $student,
                    'score'         => $result->final_score,
                    'calculated_at' => $result->calculated_at,
                    'status_in_tab' => $student->final_status === 'accepted' ? 'accepted' : 'rejected',
                ];
            });
    }

    /**
     * Ranking Bahasa — HANYA siswa yang memilih bahasa.
     * Tidak ada badge cross/dual di tab ini.
     * Status lulus = final_status accepted (karena mereka bersaing di quota bahasa sendiri).
     */
    private function getLanguageRanking(int $academicYearId): Collection
    {
        return SawResult::with(['student', 'student.user'])
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', 'language')
            ->whereHas('student', fn($q) => $q
                ->where('validation_status', 'valid')
                ->where('specialization', 'language') // hanya siswa yang memilih bahasa
            )
            ->orderBy('rank')
            ->get()
            ->map(function (SawResult $result) {
                $student = $result->student;

                return [
                    'rank'          => $result->rank,
                    'student'       => $student,
                    'score'         => $result->final_score,
                    'calculated_at' => $result->calculated_at,
                    'status_in_tab' => $student->final_status === 'accepted' ? 'accepted' : 'rejected',
                ];
            });
    }

    /**
     * Ranking Regular — FCFS, tidak ada SAW.
     */
    private function getRegularRanking(int $academicYearId): Collection
    {
        return Student::with('user')
            ->where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid')
            ->where('specialization', 'regular')
            ->orderBy('validated_at')
            ->get();
    }

    private function buildStats(int $academicYearId): array
    {
        $base = Student::where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid');

        return [
            'total_students'  => (clone $base)->count(),
            'total_passed'    => (clone $base)->where('final_status', 'accepted')->count(),
            'tahfiz_choice'   => (clone $base)->where('specialization', 'tahfiz')->count(),
            'language_choice' => (clone $base)->where('specialization', 'language')->count(),
            'regular_choice'  => (clone $base)->where('specialization', 'regular')->count(),
            'dual_pass_count' => (clone $base)->where('dual_pass', true)->count(),
            'cross_accepted'  => (clone $base)->where('cross_accepted', true)->count(),
        ];
    }

    private function getProgramConfig(string $specialization): array
    {
        return match ($specialization) {
            'tahfiz' => [
                'label'        => 'Tahfiz',
                'badge_color'  => 'bg-green-100 text-green-800',
                'avatar_color' => 'bg-gradient-to-br from-green-500 to-emerald-600',
                'icon'         => '<svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
            ],
            'language' => [
                'label'        => 'Bahasa',
                'badge_color'  => 'bg-blue-100 text-blue-800',
                'avatar_color' => 'bg-gradient-to-br from-blue-500 to-cyan-600',
                'icon'         => '<svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>',
            ],
            default => [
                'label'        => 'Regular',
                'badge_color'  => 'bg-purple-100 text-purple-800',
                'avatar_color' => 'bg-gradient-to-br from-purple-500 to-pink-600',
                'icon'         => '<svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
        };
    }
}