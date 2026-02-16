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

    /**
     * Mapping kolom ReportGrade ke keyword nama criteria.
     * Jika nama criteria mengandung salah satu keyword ini (case-insensitive),
     * nilainya akan diambil otomatis dari ReportGrade dan tidak bisa diedit.
     */
    protected array $reportGradeMapping = [
        // keyword dalam nama criteria => kolom di tabel report_grades
        'agama'    => 'islamic_studies',
        'pai'      => 'islamic_studies',
        'inggris'  => 'english_language',
        'english'  => 'english_language',
    ];

    public function __construct(
        SawService $sawService,
        RankingService $rankingService,
        CriteriaValueSyncService $syncService
    ) {
        $this->sawService = $sawService;
        $this->rankingService = $rankingService;
        $this->syncService = $syncService;
    }

    // ---------------------------------------------------------------------------
    // HELPER: tentukan apakah sebuah criteria nilainya diambil dari ReportGrade
    // ---------------------------------------------------------------------------

    /**
     * Kembalikan nama kolom ReportGrade yang sesuai dengan criteria,
     * atau null jika criteria ini bukan dari ReportGrade.
     */
    protected function getReportGradeColumn(Criteria $criteria): ?string
    {
        $name = mb_strtolower($criteria->name . ' ' . ($criteria->code ?? ''));
        foreach ($this->reportGradeMapping as $keyword => $column) {
            if (str_contains($name, $keyword)) {
                return $column;
            }
        }
        return null;
    }

    /**
     * Buat array [criteria_id => kolom_report_grade] untuk semua criteria
     * yang nilainya bersumber dari ReportGrade.
     */
    protected function buildReportGradeCriteriaMap(\Illuminate\Support\Collection $criterias): array
    {
        $map = [];
        foreach ($criterias as $criteria) {
            $col = $this->getReportGradeColumn($criteria);
            if ($col !== null) {
                $map[$criteria->id] = $col;
            }
        }
        return $map;
    }

    // ---------------------------------------------------------------------------
    // INDEX
    // ---------------------------------------------------------------------------

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

        $students = Student::with(['criterionValues.criteria', 'user'])
            ->where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->paginate(20);

        $criterias = Criteria::active()
            ->where('specialization', $specialization)
            ->ordered()
            ->get();

        $allCriterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $totalStudents     = $students->total();
        $completedStudents = 0;

        foreach ($students as $student) {
            $valueCount = $student->criterionValues()
                ->whereIn('criteria_id', $allCriterias->pluck('id'))
                ->count();

            if ($valueCount === $allCriterias->count()) {
                $completedStudents++;
            }
        }

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
            'students', 'criterias', 'allCriterias', 'activeYear',
            'specialization', 'totalStudents', 'completedStudents',
            'tahfizStudents', 'languageStudents', 'regularStudents'
        ));
    }

    // ---------------------------------------------------------------------------
    // CREATE (FORM)
    // ---------------------------------------------------------------------------

    /**
     * Show form for inputting criterion values for a student.
     * Nilai agama & bahasa inggris diambil otomatis dari ReportGrade.
     */
    public function create(Student $student)
    {
        if ($student->validation_status !== 'valid') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa belum tervalidasi');
        }

        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('warning', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW. Mereka akan otomatis masuk ranking FCFS.');
        }

        // Ambil semua criteria aktif untuk tahfiz & language
        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        // Existing values yang sudah tersimpan
        $existingValues = StudentCriterionValue::where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        // Map: criteria yang nilainya dari ReportGrade
        $reportGradeCriteriaMap = $this->buildReportGradeCriteriaMap($criterias);

        // ReportGrade siswa (jika ada)
        $reportGrade = $student->reportGrade;

        // Sinkronkan nilai dari ReportGrade ke $existingValues (hanya untuk tampilan,
        // belum disimpan ke DB — penyimpanan terjadi di store())
        foreach ($reportGradeCriteriaMap as $criteriaId => $column) {
            if ($reportGrade && isset($reportGrade->{$column})) {
                // Jika belum ada di DB, tampilkan nilai dari ReportGrade
                if (!$existingValues->has($criteriaId)) {
                    $existingValues->put($criteriaId, (object)[
                        'raw_value' => $reportGrade->{$column},
                        'notes'     => null,
                        'from_report_grade' => true, // flag untuk view
                    ]);
                }
            }
        }

        $totalCriteria     = $criterias->count();
        $completedCriteria = $existingValues->count();
        $progress          = [
            'percentage' => $totalCriteria > 0 ? ($completedCriteria / $totalCriteria) * 100 : 0,
            'completed'  => $completedCriteria,
            'total'      => $totalCriteria,
        ];

        $syncStatus = $this->syncService->checkSyncStatus($student);

        return view('committee.criterion-values.create', compact(
            'student',
            'criterias',
            'existingValues',
            'progress',
            'syncStatus',
            'reportGradeCriteriaMap', // dikirim ke view agar tahu field mana yg readonly
            'reportGrade'
        ));
    }

    // ---------------------------------------------------------------------------
    // STORE
    // ---------------------------------------------------------------------------

    /**
     * Store criterion values for a student.
     * Nilai agama & bahasa inggris diambil langsung dari ReportGrade,
     * bukan dari request (field-nya tidak ada di form / readonly).
     */
    public function store(Request $request, Student $student)
    {
        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW');
        }

        // Ambil semua criteria untuk menentukan field mana yg dari ReportGrade
        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $reportGradeCriteriaMap = $this->buildReportGradeCriteriaMap($criterias);
        $reportGrade            = $student->reportGrade;

        // ID criteria yang TIDAK dari ReportGrade → harus divalidasi dari input
        $editableCriteriaIds = $criterias->pluck('id')
            ->diff(array_keys($reportGradeCriteriaMap))
            ->values()
            ->all();

        // Validasi hanya untuk field yang bisa diedit
        $validated = $request->validate([
            'values'   => 'required|array',
            'values.*' => 'required|numeric|min:0|max:100',
            'notes'    => 'nullable|array',
            'notes.*'  => 'nullable|string|max:500',
        ], [
            'values.required'   => 'Minimal satu nilai kriteria harus diisi',
            'values.*.required' => 'Nilai kriteria harus diisi',
            'values.*.numeric'  => 'Nilai kriteria harus berupa angka',
            'values.*.min'      => 'Nilai kriteria minimal 0',
            'values.*.max'      => 'Nilai kriteria maksimal 100',
            'notes.*.max'       => 'Catatan maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();

            // ----------------------------------------------------------------
            // 1. Simpan nilai dari form (criteria yang bisa diedit)
            // ----------------------------------------------------------------
            foreach ($validated['values'] as $criteriaId => $value) {
                // Hanya proses criteria yang BUKAN dari ReportGrade
                if (array_key_exists($criteriaId, $reportGradeCriteriaMap)) {
                    continue;
                }

                $criteria = Criteria::where('id', $criteriaId)
                    ->where('is_active', true)
                    ->whereIn('specialization', ['tahfiz', 'language'])
                    ->first();

                if (!$criteria) {
                    continue;
                }

                StudentCriterionValue::updateOrCreate(
                    ['student_id' => $student->id, 'criteria_id' => $criteriaId],
                    [
                        'raw_value' => $value,
                        'notes'     => $validated['notes'][$criteriaId] ?? null,
                    ]
                );
            }

            // ----------------------------------------------------------------
            // 2. Simpan / perbarui nilai dari ReportGrade secara otomatis
            // ----------------------------------------------------------------
            foreach ($reportGradeCriteriaMap as $criteriaId => $column) {
                if (!$reportGrade) {
                    // Jika tidak ada ReportGrade, lewati (jangan hapus nilai lama)
                    continue;
                }

                $reportValue = $reportGrade->{$column};

                if ($reportValue === null) {
                    continue; // Kolom kosong di ReportGrade, lewati
                }

                StudentCriterionValue::updateOrCreate(
                    ['student_id' => $student->id, 'criteria_id' => $criteriaId],
                    [
                        'raw_value' => $reportValue,
                        'notes'     => 'Diambil otomatis dari Rapor',
                    ]
                );
            }

            DB::commit();

            // Cek apakah semua criteria sudah terisi
            $criteriaCount = Criteria::active()
                ->whereIn('specialization', ['tahfiz', 'language'])
                ->count();

            $valueCount = StudentCriterionValue::where('student_id', $student->id)
                ->whereHas('criteria', function ($query) {
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

    // ---------------------------------------------------------------------------
    // SYNC & BATCH
    // ---------------------------------------------------------------------------

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

    // ---------------------------------------------------------------------------
    // SAW & ACCEPTANCE
    // ---------------------------------------------------------------------------

    /**
     * Calculate SAW untuk SEMUA siswa di SEMUA spesialisasi
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
                'user_id'          => auth()->id(),
                'timestamp'        => now(),
            ]);

            $result = $this->sawService->calculateAllScores(
                $activeYear->id,
                auth()->id()
            );

            if (!$result['success']) {
                return redirect()->back()
                    ->with('error', $result['message']);
            }

            $totalTahfiz   = $result['data']['tahfiz']['total_students']   ?? 0;
            $totalLanguage = $result['data']['language']['total_students'] ?? 0;

            return redirect()->route('committee.saw-results.index')
                ->with('success', "✅ Perhitungan SAW berhasil! Tahfiz: {$totalTahfiz} siswa, Language: {$totalLanguage} siswa. Silakan lanjutkan ke penentuan status penerimaan.");

        } catch (\Exception $e) {
            Log::error('SAW Calculation Exception', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghitung SAW: ' . $e->getMessage());
        }
    }

    /**
     * Tentukan status penerimaan dengan 3 jalur
     */
    public function determineAcceptance(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        try {
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

            $data    = $result['data'];
            $message = "Status penerimaan berhasil ditentukan!\n";
            $message .= "• Tahfiz: {$data['tahfiz']['accepted']}/{$data['tahfiz']['quota']}\n";
            $message .= "• Language: {$data['language']['accepted']}/{$data['language']['quota']}\n";
            $message .= "• Regular: {$data['regular']['accepted']}/{$data['regular']['quota']}\n";
            $message .= "• Ditolak: {$data['rejected']['total']}";

            return redirect()->route('committee.acceptance.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Determine Acceptance Exception', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ---------------------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------------------

    /**
     * Show detail of student's criterion values
     */
    public function show(Student $student)
    {
        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $values = StudentCriterionValue::with('criteria')
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        $sawScores = null;
        if ($student->specialization !== 'regular') {
            $sawScores = $this->sawService->getStudentAllScores(
                $student->id,
                $student->academic_year_id
            );
        }

        $reportGradeCriteriaMap = $this->buildReportGradeCriteriaMap($criterias);

        return view('committee.criterion-values.show', compact(
            'student',
            'criterias',
            'values',
            'sawScores',
            'reportGradeCriteriaMap'
        ));
    }
}