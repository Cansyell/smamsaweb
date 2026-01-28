<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SawResult;
use App\Service\SpecializationService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    protected $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    /**
     * Display ranking results
     */
    public function index(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Cek apakah siswa sudah memilih peminatan
        if (empty($student->specialization)) {
            return redirect()->route('student.specialization.index')
                ->with('warning', 'Anda harus memilih peminatan terlebih dahulu untuk melihat hasil ranking');
        }

        // Ambil filter specialization dari request atau gunakan specialization siswa
        $filterSpecialization = $request->input('specialization', $student->specialization);

        // Validasi filter specialization
        if (!in_array($filterSpecialization, ['tahfiz', 'language', 'regular'])) {
            $filterSpecialization = $student->specialization;
        }

        // Ambil data ranking berdasarkan specialization
        $rankings = SawResult::with(['student.user', 'student.reportGrade', 'student.testScore'])
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $filterSpecialization)
            ->orderBy('rank', 'asc')
            ->paginate(20);

        // Ambil ranking siswa saat ini
        $myRanking = $this->specializationService->getStudentRanking($student);

        // Ambil statistik peminatan yang dipilih
        $statistics = $this->specializationService->getSpecializationStatistics(
            $student->academic_year_id,
            $filterSpecialization
        );

        // Ambil kuota informasi
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $progress = $student->getRegistrationProgress();

        return view('student.result.index', compact(
            'student',
            'rankings',
            'myRanking',
            'statistics',
            'quotaInfo',
            'filterSpecialization',
            'progress'
        ));
    }

    /**
     * Display detail calculation for student's ranking
     */
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

        // Ambil data SAW Result siswa
        $sawResult = SawResult::with(['student.reportGrade', 'student.testScore'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->first();

        if (!$sawResult) {
            return redirect()->route('student.result.index')
                ->with('info', 'Hasil perhitungan ranking belum tersedia. Mohon tunggu admin melakukan perhitungan.');
        }

        // Ambil ranking siswa
        $myRanking = $this->specializationService->getStudentRanking($student);

        // Ambil kuota informasi
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $progress = $student->getRegistrationProgress();

        return view('student.result.show', compact(
            'student',
            'sawResult',
            'myRanking',
            'quotaInfo',
            'progress'
        ));
    }

    /**
     * Display comparison with other students (anonymized)
     */
    public function comparison()
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

        // Ambil ranking siswa
        $myRanking = $this->specializationService->getStudentRanking($student);

        if (!$myRanking) {
            return redirect()->route('student.result.index')
                ->with('info', 'Hasil perhitungan ranking belum tersedia.');
        }

        // Ambil 5 ranking teratas dan 5 ranking terbawah (anonymized)
        $topRankings = SawResult::where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->orderBy('rank', 'asc')
            ->limit(5)
            ->get();

        $bottomRankings = SawResult::where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->orderBy('rank', 'desc')
            ->limit(5)
            ->get()
            ->sortBy('rank');

        // Ambil siswa di sekitar ranking (2 di atas, 2 di bawah)
        $nearbyRankings = SawResult::with(['student.user'])
            ->where('academic_year_id', $student->academic_year_id)
            ->where('specialization', $student->specialization)
            ->whereBetween('rank', [max(1, $myRanking['rank'] - 2), $myRanking['rank'] + 2])
            ->orderBy('rank', 'asc')
            ->get();

        // Statistik
        $statistics = $this->specializationService->getSpecializationStatistics(
            $student->academic_year_id,
            $student->specialization
        );

        $progress = $student->getRegistrationProgress();

        return view('student.result.comparison', compact(
            'student',
            'myRanking',
            'topRankings',
            'bottomRankings',
            'nearbyRankings',
            'statistics',
            'progress'
        ));
    }

    /**
     * Export/Print ranking card
     */
    public function card()
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

        // Ambil ranking siswa
        $myRanking = $this->specializationService->getStudentRanking($student);

        if (!$myRanking) {
            return redirect()->route('student.result.index')
                ->with('info', 'Hasil perhitungan ranking belum tersedia.');
        }

        // Ambil kuota informasi
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        // View khusus untuk print/PDF
        return view('student.result.card', compact(
            'student',
            'myRanking',
            'quotaInfo'
        ));
    }
}