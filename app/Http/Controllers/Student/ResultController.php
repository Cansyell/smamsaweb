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
     * Display ranking results for all specializations
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

        // Ambil filter specialization dari request, default ke pilihan siswa
        $filterSpecialization = $request->input('specialization', $student->specialization);

        // Validasi filter - siswa bisa lihat semua tab tapi ada highlight untuk pilihan mereka
        if (!in_array($filterSpecialization, ['tahfiz', 'language', 'regular'])) {
            $filterSpecialization = $student->specialization;
        }

        $rankings = null;
        $myPosition = null;
        $statistics = null;

        // Untuk REGULAR: gunakan sistem FCFS (tidak ada SAW ranking)
        if ($filterSpecialization === 'regular') {
            // Ambil siswa regular yang sudah divalidasi, diurutkan berdasarkan validated_at (FCFS)
            $rankings = Student::with(['user', 'reportGrade', 'testScore'])
                ->where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->orderBy('validated_at', 'asc')
                ->paginate(20);

            // Ambil posisi siswa dalam antrian FCFS (jika siswa pilih regular)
            if ($student->specialization === 'regular' && $student->validation_status === 'valid') {
                $myPosition = Student::where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', 'regular')
                    ->where('validation_status', 'valid')
                    ->where('validated_at', '<=', $student->validated_at)
                    ->count();
            }

            // Statistik untuk regular
            $totalRegular = Student::where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->count();

            $statistics = [
                'total_students' => $totalRegular,
                'average_score' => null,
                'highest_score' => null,
                'lowest_score' => null,
            ];

        } else {
            // Untuk TAHFIZ/LANGUAGE: gunakan SAW ranking
            $rankings = SawResult::with(['student.user', 'student.reportGrade', 'student.testScore'])
                ->where('academic_year_id', $student->academic_year_id)
                ->where('specialization', $filterSpecialization)
                ->orderBy('rank', 'asc')
                ->paginate(20);

            // Ambil posisi siswa (jika siswa pilih spesialisasi yang sama)
            if ($student->specialization === $filterSpecialization) {
                $myRankingData = SawResult::where('student_id', $student->id)
                    ->where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', $filterSpecialization)
                    ->first();
                
                if ($myRankingData) {
                    $myPosition = $myRankingData->rank;
                }
            }

            // Ambil statistik peminatan yang dipilih
            $statistics = $this->specializationService->getSpecializationStatistics(
                $student->academic_year_id,
                $filterSpecialization
            );
        }

        // Ambil ranking siswa untuk pilihan mereka sendiri (untuk card display)
        $myRanking = null;
        if ($student->specialization === 'regular') {
            if ($student->validation_status === 'valid') {
                $fcfsPosition = Student::where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', 'regular')
                    ->where('validation_status', 'valid')
                    ->where('validated_at', '<=', $student->validated_at)
                    ->count();

                $totalRegular = Student::where('academic_year_id', $student->academic_year_id)
                    ->where('specialization', 'regular')
                    ->where('validation_status', 'valid')
                    ->count();

                $myRanking = [
                    'rank' => $fcfsPosition,
                    'total_students' => $totalRegular,
                    'final_score' => null,
                    'is_accepted' => $student->final_status === 'accepted',
                    'specialization' => 'regular',
                ];
            }
        } else {
            $myRanking = $this->specializationService->getStudentRanking($student);
        }

        // Ambil kuota informasi untuk semua spesialisasi
        $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

        $progress = $student->getRegistrationProgress();

        return view('student.result.index', compact(
            'student',
            'rankings',
            'myRanking',
            'myPosition',
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

        // Untuk siswa REGULAR: redirect ke halaman index karena tidak ada detail perhitungan SAW
        if ($student->specialization === 'regular') {
            return redirect()->route('student.result.index')
                ->with('info', 'Siswa regular menggunakan sistem First Come First Serve (FCFS), tidak ada perhitungan SAW.');
        }

        // Ambil data SAW Result siswa (untuk tahfiz/language)
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

        // Untuk siswa REGULAR: tidak ada comparison karena menggunakan FCFS
        if ($student->specialization === 'regular') {
            return redirect()->route('student.result.index')
                ->with('info', 'Fitur perbandingan tidak tersedia untuk siswa regular (FCFS).');
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

        // Untuk REGULAR: buat card khusus FCFS
        if ($student->specialization === 'regular') {
            if ($student->validation_status !== 'valid') {
                return redirect()->route('student.result.index')
                    ->with('info', 'Berkas Anda belum divalidasi.');
            }

            // Hitung posisi FCFS
            $fcfsPosition = Student::where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->where('validated_at', '<=', $student->validated_at)
                ->count();

            $totalRegular = Student::where('academic_year_id', $student->academic_year_id)
                ->where('specialization', 'regular')
                ->where('validation_status', 'valid')
                ->count();

            $myRanking = [
                'rank' => $fcfsPosition,
                'total_students' => $totalRegular,
                'final_score' => null,
                'is_accepted' => $student->final_status === 'accepted',
                'specialization' => 'regular',
                'validated_at' => $student->validated_at,
            ];

            $quotaInfo = $this->specializationService->getQuotaInformation($student->academic_year_id);

            return view('student.result.card-regular', compact(
                'student',
                'myRanking',
                'quotaInfo'
            ));
        }

        // Untuk TAHFIZ/LANGUAGE: gunakan card SAW
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