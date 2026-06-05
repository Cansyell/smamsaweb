<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SawResult;
use App\Service\SpecializationService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct(protected SpecializationService $specializationService) {}

    // -----------------------------------------------------------------------
    // INDEX
    // -----------------------------------------------------------------------

    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.index')
                ->with('warning', 'Anda harus memilih peminatan terlebih dahulu');
        }

        $activeYear = AcademicYear::getActiveYear();
        if (!$activeYear || $activeYear->result_status !== 'published') {
            return view('student.result.not-published', compact('activeYear'));
        }

        $filterSpecialization = $this->resolveFilter($request, $student);

        [$rankings, $myPosition, $statistics] = $this->buildRankingData(
            $student, $filterSpecialization, $activeYear
        );

        $myRanking = $this->resolveMyRanking($student, $activeYear);
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);
        $progress  = $student->getRegistrationProgress();

        $dualPassInfo = $student->dual_pass ? [
            'recommended'            => $student->recommended_specialization,
            'chosen'                 => $student->specialization,
            'already_at_recommended' => $student->specialization === $student->recommended_specialization,
        ] : null;

        return view('student.result.index', compact(
            'student', 'rankings', 'myRanking', 'myPosition',
            'statistics', 'quotaInfo', 'filterSpecialization', 'progress',
            'dualPassInfo', 'activeYear'
        ));
    }

    // -----------------------------------------------------------------------
    // SHOW
    // -----------------------------------------------------------------------

    public function show()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.index')
                ->with('warning', 'Anda belum memilih peminatan');
        }

        $activeYear = AcademicYear::getActiveYear();
        if (!$activeYear || $activeYear->result_status !== 'published') {
            return view('student.result.not-published', compact('activeYear'));
        }

        if ($student->specialization === 'regular') {
            return redirect()->route('student.result.index')
                ->with('info', 'Siswa regular menggunakan sistem FCFS, tidak ada perhitungan SAW.');
        }

        $sawResult = SawResult::with(['student.reportGrade', 'student.testScore'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->first();

        if (!$sawResult) {
            return redirect()->route('student.result.index')
                ->with('info', 'Hasil perhitungan ranking belum tersedia.');
        }

        $myRanking = $this->specializationService->getStudentRanking($student);
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);
        $progress  = $student->getRegistrationProgress();

        $dualPassInfo = $student->dual_pass ? [
            'recommended'            => $student->recommended_specialization,
            'chosen'                 => $student->specialization,
            'already_at_recommended' => $student->specialization === $student->recommended_specialization,
        ] : null;

        return view('student.result.show', compact(
            'student', 'sawResult', 'myRanking', 'quotaInfo', 'progress', 'dualPassInfo'
        ));
    }

    // -----------------------------------------------------------------------
    // CARD
    // -----------------------------------------------------------------------

    public function card()
    {
        $student = Student::with('sawResults')
            ->where('user_id', auth()->id())
            ->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        $activeYear = AcademicYear::getActiveYear();
        if (!$activeYear || $activeYear->result_status !== 'published') {
            return view('student.result.not-published', compact('activeYear'));
        }

        $myRanking = $this->resolveMyRanking($student, $activeYear);
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $dualPassInfo = $student->dual_pass ? [
            'recommended'            => $student->recommended_specialization,
            'chosen'                 => $student->specialization,
            'already_at_recommended' => $student->specialization === $student->recommended_specialization,
        ] : null;

        $labels = [
            'tahfiz'   => "Tahfiz Al-Qur'an",
            'language' => 'Bahasa / Internasional',
            'regular'  => 'Reguler',
        ];

        return view('student.result.card', compact(
            'student', 'myRanking', 'quotaInfo', 'dualPassInfo', 'labels'
        ));
    }

    // -----------------------------------------------------------------------
    // PRIVATE HELPERS
    // -----------------------------------------------------------------------

    private function resolveFilter(Request $request, Student $student): string
    {
        $filter = $request->input('specialization', $student->specialization);

        return in_array($filter, ['tahfiz', 'language', 'regular'])
            ? $filter
            : $student->specialization;
    }

    private function buildRankingData(Student $student, string $filter, AcademicYear $activeYear): array
    {
        if ($filter === 'regular') {
            $rankings = Student::with(['user', 'reportGrade'])
                ->where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->orderBy('validated_at')
                ->paginate(20);

            $myPosition = null;
            if ($student->specialization === 'regular' && $student->validation_status === 'valid') {
                $myPosition = Student::where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', 'regular')
                    ->where('validation_status', 'valid')
                    ->where('validated_at', '<=', $student->validated_at)
                    ->count();
            }

            $statistics = [
                'total_students' => Student::where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', 'regular')
                    ->where('validation_status', 'valid')
                    ->count(),
                'average_score'  => null,
                'highest_score'  => null,
                'lowest_score'   => null,
            ];

            return [$rankings, $myPosition, $statistics];
        }

        // ── Tahfiz / Language ──────────────────────────────────────────────
        // Hanya tampilkan siswa yang MEMILIH peminatan ini (primary_rank tidak null).
        // Diurutkan primary_rank agar nomor urut berurutan (#1, #2, #3...).
        $rankings = SawResult::with(['student.user'])
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $filter)
            ->whereNotNull('primary_rank')          // hanya pemilih peminatan ini
            ->whereHas('student', fn($q) => $q
                ->where('specialization', $filter)
                ->where('validation_status', 'valid')
            )
            ->orderBy('primary_rank')               // urut primary_rank, bukan global rank
            ->paginate(20);

        // Posisi siswa saat ini = primary_rank-nya di peminatan yang dipilih
        $myPosition = null;
        $myRankData = SawResult::where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $filter)
            ->first();
        if ($myRankData) {
            // Gunakan primary_rank jika ada (siswa memilih peminatan ini),
            // fallback ke rank global jika sedang melihat tab lain
            $myPosition = $myRankData->primary_rank ?? $myRankData->rank;
        }

        $statistics = $this->specializationService->getSpecializationStatistics(
            $student->academic_year_id,
            $filter
        );

        return [$rankings, $myPosition, $statistics];
    }

    private function resolveMyRanking(Student $student, AcademicYear $activeYear): ?array
    {
        if ($student->specialization === 'regular') {
            if ($student->validation_status !== 'valid') {
                return null;
            }

            $position = Student::where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->where('validated_at', '<=', $student->validated_at)
                ->count();

            $total = Student::where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->count();

            return [
                'rank'           => $position,
                'total_students' => $total,
                'final_score'    => null,
                'is_accepted'    => $student->final_status === 'accepted',
                'specialization' => 'regular',
                'calculated_at'  => now(),
            ];
        }

        // Untuk tahfiz/language: getStudentRanking() sudah mengembalikan primary_rank
        // dan is_accepted dari final_status DB
        return $this->specializationService->getStudentRanking($student);
    }
}