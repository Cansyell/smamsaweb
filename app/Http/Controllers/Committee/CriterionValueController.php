<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Criteria;
use App\Models\Student;
use App\Models\SawResult;
use App\Models\CriterionWeight;
use App\Models\StudentCriterionValue;
use App\Models\SpecializationQuota;
use App\Service\SawService;
use App\Service\RankingService;
use App\Service\CriteriaValueSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriterionValueController extends Controller
{
    protected $sawService;
    protected $rankingService;
    protected $syncService;

    public function __construct(
        SawService $sawService,
        RankingService $rankingService,
        CriteriaValueSyncService $syncService
    ) {
        $this->sawService = $sawService;
        $this->rankingService = $rankingService;
        $this->syncService = $syncService;
    }

    /**
     * Display list of students for criterion value input
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $specialization = $request->input('specialization', 'tahfiz');
        
        // Get students yang pilih tahfiz atau language (bukan regular)
        // Karena hanya mereka yang perlu dinilai untuk SAW
        $students = Student::with(['criterionValues.criteria', 'user'])
            ->where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->whereIn('specialization', ['tahfiz', 'language']) // HANYA tahfiz & language
            ->paginate(20);

        // Get criteria untuk specialization yang dipilih
        $criterias = Criteria::active()
            ->where('specialization', $specialization)
            ->ordered()
            ->get();

        // Get semua criteria untuk kedua spesialisasi (untuk cek kelengkapan)
        $allCriterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        // Calculate completion statistics
        $totalStudents = $students->total();
        $completedStudents = 0;
        
        // Siswa dianggap complete jika SEMUA kriteria (Tahfiz + Language) sudah terisi
        foreach ($students as $student) {
            $valueCount = $student->criterionValues()
                ->whereIn('criteria_id', $allCriterias->pluck('id'))
                ->count();
            
            if ($valueCount === $allCriterias->count()) {
                $completedStudents++;
            }
        }

        // Count students by specialization choice
        $tahfizStudents = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->where('specialization', 'tahfiz')
            ->count();
        
        $languageStudents = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->where('specialization', 'language')
            ->count();
        
        $regularStudents = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->where('specialization', 'regular')
            ->count();

        return view('committee.criterion-values.index', compact(
            'students',
            'criterias',
            'allCriterias',
            'activeYear',
            'specialization',
            'totalStudents',
            'completedStudents',
            'tahfizStudents',
            'languageStudents',
            'regularStudents'
        ));
    }

    /**
     * Show form for inputting criterion values for a student
     */
    public function create(Student $student)
    {
        // Check if student is valid
        if ($student->validation_status !== 'valid') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa belum tervalidasi');
        }

        // Check if student chose regular
        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('warning', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW. Mereka akan otomatis masuk ranking FCFS.');
        }

        // Get ALL active criteria untuk KEDUA spesialisasi (tahfiz & language)
        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        // Get existing values
        $existingValues = StudentCriterionValue::where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        // Calculate progress
        $totalCriteria = $criterias->count();
        $completedCriteria = $existingValues->count();
        $progress = [
            'percentage' => $totalCriteria > 0 ? ($completedCriteria / $totalCriteria) * 100 : 0,
            'completed' => $completedCriteria,
            'total' => $totalCriteria
        ];

        // Check sync status with report grade
        $syncStatus = $this->syncService->checkSyncStatus($student);

        return view('committee.criterion-values.create', compact(
            'student',
            'criterias',
            'existingValues',
            'progress',
            'syncStatus'
        ));
    }

    /**
     * Store criterion values for a student
     */
    public function store(Request $request, Student $student)
    {
        // Check if student chose regular
        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW');
        }

        // Validate input
        $validated = $request->validate([
            'values' => 'required|array',
            'values.*' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:500',
        ], [
            'values.required' => 'Minimal satu nilai kriteria harus diisi',
            'values.*.required' => 'Nilai kriteria harus diisi',
            'values.*.numeric' => 'Nilai kriteria harus berupa angka',
            'values.*.min' => 'Nilai kriteria minimal 0',
            'values.*.max' => 'Nilai kriteria maksimal 100',
            'notes.*.max' => 'Catatan maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['values'] as $criteriaId => $value) {
                // Verify criteria is active and for tahfiz/language
                $criteria = Criteria::where('id', $criteriaId)
                    ->where('is_active', true)
                    ->whereIn('specialization', ['tahfiz', 'language'])
                    ->first();

                if (!$criteria) {
                    continue;
                }

                StudentCriterionValue::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'criteria_id' => $criteriaId,
                    ],
                    [
                        'raw_value' => $value,
                        'notes' => $validated['notes'][$criteriaId] ?? null,
                    ]
                );
            }

            DB::commit();

            // Check if all criteria are filled (hanya tahfiz & language)
            $criteriaCount = Criteria::active()
                ->whereIn('specialization', ['tahfiz', 'language'])
                ->count();
            
            $valueCount = StudentCriterionValue::where('student_id', $student->id)
                ->whereHas('criteria', function($query) {
                    $query->where('is_active', true)
                          ->whereIn('specialization', ['tahfiz', 'language']);
                })
                ->count();

            if ($valueCount === $criteriaCount) {
                return redirect()->route('committee.criterion-values.index')
                    ->with('success', 'Nilai kriteria berhasil disimpan. Semua kriteria untuk siswa ini telah lengkap.');
            }

            return redirect()->route('committee.criterion-values.create', $student)
                ->with('success', 'Nilai kriteria berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing criterion values: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan nilai kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Sync nilai dari Report Grade ke Criterion Values
     */
    public function syncFromReportGrade(Student $student)
    {
        $result = $this->syncService->syncReportGradeToValues($student);

        if ($result['success']) {
            return redirect()->route('committee.criterion-values.create', $student)
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }

    /**
     * Batch sync untuk semua siswa
     */
    public function batchSyncReportGrades(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $result = $this->syncService->batchSyncReportGrades($activeYear->id);

        if ($result['success']) {
            return redirect()->route('committee.criterion-values.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }

    /**
     * Calculate SAW untuk SEMUA siswa di SEMUA spesialisasi
     * Setiap siswa akan mendapat 2 SAW scores (Tahfiz & Language)
     */
    public function calculateSaw(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        try {
            Log::info('SAW Calculation Started for All Specializations', [
                'academic_year_id' => $activeYear->id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            // Calculate SAW untuk KEDUA spesialisasi sekaligus
            $result = $this->sawService->calculateAllScores(
                $activeYear->id,
                auth()->id()
            );

            if (!$result['success']) {
                return redirect()->back()
                    ->with('error', $result['message']);
            }

            $totalTahfiz = $result['data']['tahfiz']['total_students'] ?? 0;
            $totalLanguage = $result['data']['language']['total_students'] ?? 0;

            return redirect()->route('committee.saw-results.index')
                ->with('success', "✅ Perhitungan SAW berhasil! Tahfiz: {$totalTahfiz} siswa, Language: {$totalLanguage} siswa. Silakan lanjutkan ke penentuan status penerimaan.");

        } catch (\Exception $e) {
            Log::error('SAW Calculation Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghitung SAW: ' . $e->getMessage());
        }
    }

    /**
     * Tentukan status penerimaan dengan 3 jalur (Tahfiz → Language → Regular)
     */
    public function determineAcceptance(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        try {
            // Pastikan SAW sudah dihitung
            $tahfizResults = SawResult::where('academic_year_id', $activeYear->id)
                ->where('specialization', 'tahfiz')
                ->exists();
            
            $languageResults = SawResult::where('academic_year_id', $activeYear->id)
                ->where('specialization', 'language')
                ->exists();

            if (!$tahfizResults || !$languageResults) {
                return redirect()->back()
                    ->with('error', 'Perhitungan SAW belum dilakukan. Silakan hitung SAW terlebih dahulu.');
            }

            $result = $this->rankingService->determineAcceptanceStatus($activeYear->id);

            if (!$result['success']) {
                return redirect()->back()
                    ->with('error', $result['message']);
            }

            $data = $result['data'];
            $message = "Status penerimaan berhasil ditentukan!\n";
            $message .= "• Tahfiz: {$data['tahfiz']['accepted']}/{$data['tahfiz']['quota']}\n";
            $message .= "• Language: {$data['language']['accepted']}/{$data['language']['quota']}\n";
            $message .= "• Regular: {$data['regular']['accepted']}/{$data['regular']['quota']}\n";
            $message .= "• Ditolak: {$data['rejected']['total']}";

            return redirect()->route('committee.acceptance.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Determine Acceptance Exception', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show detail of student's criterion values
     */
    public function show(Student $student)
    {
        // Get criteria untuk tahfiz & language saja
        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $values = StudentCriterionValue::with('criteria')
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        // Get SAW results untuk kedua spesialisasi (jika ada)
        $sawScores = null;
        if ($student->specialization !== 'regular') {
            $sawScores = $this->sawService->getStudentAllScores(
                $student->id,
                $student->academic_year_id
            );
        }

        return view('committee.criterion-values.show', compact(
            'student',
            'criterias',
            'values',
            'sawScores'
        ));
    }
}