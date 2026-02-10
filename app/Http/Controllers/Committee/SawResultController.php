<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SawResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SawResultController extends Controller
{
    /**
     * Display a listing of SAW results with tabs
     * Tab Global: Semua siswa dengan status kelulusan (tidak ada ranking global)
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()
                ->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        /** =============================
         *  1. DATA GLOBAL - SEMUA SISWA
         *  Tidak ada ranking global, hanya menampilkan:
         *  - Semua siswa
         *  - Status kelulusan
         *  - Ranking Tahfiz (jika lulus & ikut Tahfiz)
         *  - Ranking Bahasa (jika lulus & ikut Bahasa)
         *  ============================= */
        $allStudents = $this->getAllStudentsData($activeYear->id);

        /** =============================
         *  2. RANKING TAHFIZ (SAW)
         *  ============================= */
        $tahfizRanking = $this->getTahfizRanking($activeYear->id);

        /** =============================
         *  3. RANKING BAHASA (SAW)
         *  ============================= */
        $languageRanking = $this->getLanguageRanking($activeYear->id);

        /** =============================
         *  4. RANKING REGULAR (FCFS)
         *  ============================= */
        $regularRanking = $this->getRegularRanking($activeYear->id);

        /** =============================
         *  Statistik
         *  ============================= */
        $stats = [
            'total_students' => Student::where('academic_year_id', $activeYear->id)
                ->where('validation_status', 'valid')
                ->count(),

            'total_passed' => Student::where('academic_year_id', $activeYear->id)
                ->where('validation_status', 'valid')
                ->where('final_status', 'accepted')
                ->count(),

            'tahfiz_choice' => Student::where('academic_year_id', $activeYear->id)
                ->where('validation_status', 'valid')
                ->where('specialization', 'tahfiz')
                ->count(),

            'language_choice' => Student::where('academic_year_id', $activeYear->id)
                ->where('validation_status', 'valid')
                ->where('specialization', 'language')
                ->count(),

            'regular_choice' => Student::where('academic_year_id', $activeYear->id)
                ->where('validation_status', 'valid')
                ->where('specialization', 'regular')
                ->count(),
        ];

        return view('committee.saw-results.index', compact(
            'allStudents',
            'tahfizRanking',
            'languageRanking',
            'regularRanking',
            'activeYear',
            'stats'
        ));
    }

    /**
     * Get All Students Data untuk Tab Global
     * Menampilkan semua siswa dengan:
     * - Status kelulusan
     * - Ranking Tahfiz (jika lulus & ikut Tahfiz)
     * - Ranking Bahasa (jika lulus & ikut Bahasa)
     * - Tanggal kelulusan (jika ada)
     * 
     * TIDAK ADA RANKING GLOBAL
     */
    private function getAllStudentsData(int $academicYearId): Collection
    {
        // Ambil semua siswa valid
        $students = Student::with('user')
            ->where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid')
            ->orderBy('full_name', 'asc')
            ->get();

        // Ambil SAW Results untuk Tahfiz dan Bahasa
        $sawResults = SawResult::where('academic_year_id', $academicYearId)
            ->get()
            ->groupBy(function ($item) {
                return $item->student_id . '-' . $item->specialization;
            });

        return $students->map(function ($student) use ($sawResults) {
            // Ambil hasil SAW untuk Tahfiz dan Bahasa
            $tahfizResult = $sawResults->get($student->id . '-tahfiz')?->first();
            $languageResult = $sawResults->get($student->id . '-language')?->first();

            // Tentukan warna dan icon berdasarkan program
            $programConfig = $this->getProgramConfig($student->specialization);

            // Tentukan status kelulusan
            $final_status = $student->final_status?? 'pending';

            return [
                'student' => $student,
                'final_status' => $final_status,
                'validated_at' => $student->validated_at,
                
                // Program info
                'program_label' => $programConfig['label'],
                'program_badge_color' => $programConfig['badge_color'],
                'program_icon' => $programConfig['icon'],
                'avatar_color' => $programConfig['avatar_color'],
                
                // Tahfiz Ranking - hanya ditampilkan jika LULUS dan mengikuti Tahfiz
                'tahfiz_rank' => ($final_status === 'accepted' && $tahfizResult) ? $tahfizResult->rank : null,
                'tahfiz_score' => ($final_status === 'accepted' && $tahfizResult) ? $tahfizResult->final_score : null,
                
                // Language Ranking - hanya ditampilkan jika LULUS dan mengikuti Bahasa
                'language_rank' => ($final_status === 'accepted' && $languageResult) ? $languageResult->rank : null,
                'language_score' => ($final_status === 'accepted' && $languageResult) ? $languageResult->final_score : null,
            ];
        });
    }

    /**
     * Get program configuration (colors, icons, labels)
     */
    private function getProgramConfig(string $specialization): array
    {
        $configs = [
            'tahfiz' => [
                'label' => 'Tahfiz',
                'badge_color' => 'bg-green-100 text-green-800',
                'avatar_color' => 'bg-gradient-to-br from-green-500 to-emerald-600',
                'icon' => '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                          </svg>',
            ],
            'language' => [
                'label' => 'Bahasa',
                'badge_color' => 'bg-blue-100 text-blue-800',
                'avatar_color' => 'bg-gradient-to-br from-blue-500 to-cyan-600',
                'icon' => '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                          </svg>',
            ],
            'regular' => [
                'label' => 'Regular',
                'badge_color' => 'bg-purple-100 text-purple-800',
                'avatar_color' => 'bg-gradient-to-br from-purple-500 to-pink-600',
                'icon' => '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>',
            ],
        ];

        return $configs[$specialization] ?? $configs['regular'];
    }

    /**
     * Get Tahfiz Ranking (berdasarkan SAW)
     */
    private function getTahfizRanking(int $academicYearId): Collection
    {
        $results = SawResult::with(['student', 'student.user'])
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', 'tahfiz')
            ->orderBy('rank', 'asc')
            ->get();

        return $results->map(function ($result) {
            return [
                'rank' => $result->rank,
                'student' => $result->student,
                'score' => $result->final_score,
                'calculated_at' => $result->calculated_at,
            ];
        });
    }

    /**
     * Get Language Ranking (berdasarkan SAW)
     */
    private function getLanguageRanking(int $academicYearId): Collection
    {
        $results = SawResult::with(['student', 'student.user'])
            ->where('academic_year_id', $academicYearId)
            ->where('specialization', 'language')
            ->orderBy('rank', 'asc')
            ->get();

        return $results->map(function ($result) {
            return [
                'rank' => $result->rank,
                'student' => $result->student,
                'score' => $result->final_score,
                'calculated_at' => $result->calculated_at,
            ];
        });
    }

    /**
     * Get Regular Ranking (berdasarkan waktu pendaftaran - FCFS)
     */
    private function getRegularRanking(int $academicYearId): Collection
    {
        return Student::with('user')
            ->where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid')
            ->where('specialization', 'regular')
            ->orderBy('created_at', 'asc') // First Come First Serve
            ->get();
    }

    /**
     * Display the specified student's SAW result
     */
    public function show(Student $student)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()
                ->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $tahfizResult = SawResult::where([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id,
            'specialization' => 'tahfiz',
        ])->first();

        $languageResult = SawResult::where([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id,
            'specialization' => 'language',
        ])->first();

        $totalTahfizStudents = SawResult::where('academic_year_id', $activeYear->id)
            ->where('specialization', 'tahfiz')
            ->count();

        $totalLanguageStudents = SawResult::where('academic_year_id', $activeYear->id)
            ->where('specialization', 'language')
            ->count();

        return view('committee.saw-results.show', compact(
            'student',
            'tahfizResult',
            'languageResult',
            'totalTahfizStudents',
            'totalLanguageStudents'
        ));
    }

    /**
     * Export ranking data (optional)
     */
    public function export(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $type = $request->input('type', 'all'); // global, tahfiz, language, regular, all

        $data = [];

        if (in_array($type, ['global', 'all'])) {
            $data['global'] = $this->getAllStudentsData($activeYear->id);
        }

        if (in_array($type, ['tahfiz', 'all'])) {
            $data['tahfiz'] = $this->getTahfizRanking($activeYear->id);
        }

        if (in_array($type, ['language', 'all'])) {
            $data['language'] = $this->getLanguageRanking($activeYear->id);
        }

        if (in_array($type, ['regular', 'all'])) {
            $data['regular'] = $this->getRegularRanking($activeYear->id);
        }

        // Implement export logic here (Excel, PDF, etc.)
        // Return download response

        return back()->with('success', 'Export berhasil');
    }
}