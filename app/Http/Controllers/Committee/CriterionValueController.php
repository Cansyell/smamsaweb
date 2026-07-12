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
        'agama'   => 'islamic_studies',
        'pai'     => 'islamic_studies',
        'inggris' => 'english_language',
        'english' => 'english_language',
    ];

    public function __construct(
        SawService $sawService,
        RankingService $rankingService,
        CriteriaValueSyncService $syncService
    ) {
        $this->sawService    = $sawService;
        $this->rankingService = $rankingService;
        $this->syncService   = $syncService;
    }

    // HELPERS

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

    /**
     * Format exception menjadi string lengkap untuk ditampilkan ke user (debug mode).
     */
    protected function formatException(\Throwable $e): string
    {
        return sprintf(
            "[%s]\n%s\n\nFile : %s\nLine : %d\n\n--- Stack Trace ---\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    /**
     * Kembalikan redirect back dengan error lengkap.
     * Di production (APP_DEBUG=false), detail trace disembunyikan dari session
     * tapi tetap masuk ke Log.
     */
    protected function redirectWithException(\Throwable $e, string $context = '')
    {
        $detail = $this->formatException($e);

        Log::error(($context ? "[$context] " : '') . $e->getMessage(), [
            'exception' => get_class($e),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTraceAsString(),
        ]);

        return redirect()->back()
            ->with('error', ($context ? "[$context] " : '') . $e->getMessage())
            ->with('error_detail', config('app.debug') ? $detail : null);
    }

    // INDEX

    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $specialization = $request->input('specialization', 'tahfiz');

        $students = Student::with(['criterionValues.criteria', 'user'])
            ->where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->paginate(30);

        $criterias = Criteria::active()
            ->where('specialization', $specialization)
            ->ordered()
            ->get();

        $allCriterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $allCriteriaIds    = $allCriterias->pluck('id');
        $totalStudents     = $students->total();
        $completedStudents = 0;

        foreach ($students as $student) {
            $valueCount = $student->criterionValues()
                ->whereIn('criteria_id', $allCriteriaIds)
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

    // CREATE (FORM)

    public function create(Student $student)
    {
        if ($student->validation_status !== 'valid') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa belum tervalidasi.');
        }

        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('warning', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW. Mereka akan otomatis masuk ranking FCFS.');
        }

        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $existingValues = StudentCriterionValue::where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        $reportGradeCriteriaMap = $this->buildReportGradeCriteriaMap($criterias);
        $reportGrade            = $student->reportGrade;

        // Tampilkan nilai ReportGrade pada form (belum disimpan, hanya untuk preview)
        foreach ($reportGradeCriteriaMap as $criteriaId => $column) {
            if ($reportGrade && isset($reportGrade->{$column}) && !$existingValues->has($criteriaId)) {
                $existingValues->put($criteriaId, (object) [
                    'raw_value'         => $reportGrade->{$column},
                    'notes'             => null,
                    'from_report_grade' => true,
                ]);
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
            'reportGradeCriteriaMap',
            'reportGrade'
        ));
    }

    // STORE

    public function store(Request $request, Student $student)
    {
        if ($student->specialization === 'regular') {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Siswa yang memilih Regular tidak perlu dinilai dengan SAW.');
        }

        $criterias = Criteria::active()
            ->whereIn('specialization', ['tahfiz', 'language'])
            ->ordered()
            ->get();

        $reportGradeCriteriaMap = $this->buildReportGradeCriteriaMap($criterias);
        $reportGrade            = $student->reportGrade;

        $validated = $request->validate([
            'values'   => 'required|array',
            'values.*' => 'required|numeric|min:0|max:100',
            'notes'    => 'nullable|array',
            'notes.*'  => 'nullable|string|max:500',
        ], [
            'values.required'   => 'Minimal satu nilai kriteria harus diisi.',
            'values.*.required' => 'Nilai kriteria harus diisi.',
            'values.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'values.*.min'      => 'Nilai kriteria minimal 0.',
            'values.*.max'      => 'Nilai kriteria maksimal 100.',
            'notes.*.max'       => 'Catatan maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan nilai dari form (criteria yang bisa diedit)
            foreach ($validated['values'] as $criteriaId => $value) {
                if (array_key_exists($criteriaId, $reportGradeCriteriaMap)) {
                    continue; // Nilai ini diambil dari ReportGrade, skip
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

            // 2. Simpan nilai dari ReportGrade secara otomatis
            foreach ($reportGradeCriteriaMap as $criteriaId => $column) {
                if (!$reportGrade) {
                    continue;
                }

                $reportValue = $reportGrade->{$column} ?? null;

                if ($reportValue === null) {
                    continue;
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

            $criteriaCount = Criteria::active()
                ->whereIn('specialization', ['tahfiz', 'language'])
                ->count();

            $valueCount = StudentCriterionValue::where('student_id', $student->id)
                ->whereHas('criteria', fn ($q) => $q->where('is_active', true)
                    ->whereIn('specialization', ['tahfiz', 'language']))
                ->count();

            if ($valueCount === $criteriaCount) {
                return redirect()->route('committee.criterion-values.index')
                    ->with('success', 'Nilai kriteria berhasil disimpan. Semua kriteria untuk siswa ini telah lengkap.');
            }

            return redirect()->route('committee.criterion-values.create', $student)
                ->with('success', 'Nilai kriteria berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->redirectWithException($e, 'Store Criterion Values');
        }
    }

    // SYNC & BATCH

    public function syncFromReportGrade(Student $student)
    {
        try {
            $result = $this->syncService->syncReportGradeToValues($student);

            if ($result['success']) {
                return redirect()->route('committee.criterion-values.create', $student)
                    ->with('success', $result['message']);
            }

            return redirect()->back()
                ->with('error', $result['message']);

        } catch (\Throwable $e) {
            return $this->redirectWithException($e, 'Sync From Report Grade');
        }
    }

    public function batchSyncReportGrades(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        try {
            $result = $this->syncService->batchSyncReportGrades($activeYear->id);

            if ($result['success']) {
                return redirect()->route('committee.criterion-values.index')
                    ->with('success', $result['message']);
            }

            return redirect()->back()
                ->with('error', $result['message']);

        } catch (\Throwable $e) {
            return $this->redirectWithException($e, 'Batch Sync Report Grades');
        }
    }

    // SAW & ACCEPTANCE

    public function calculateSaw(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $pendingCount = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'pending')
            ->count();

        if ($pendingCount > 0) {
            return redirect()->back()
                ->with('error', "Tidak dapat menghitung SAW. Masih ada {$pendingCount} siswa berstatus 'pending'. Validasi semua siswa terlebih dahulu.");
        }

        try {
            Log::info('SAW Calculation Started', [
                'academic_year_id' => $activeYear->id,
                'user_id'          => auth()->id(),
                'timestamp'        => now()->toDateTimeString(),
            ]);

            $result = $this->sawService->calculateAllScores(
                $activeYear->id,
                auth()->id()
            );

            Log::info('SAW Service Result', ['result' => $result]);

            if (!$result['success']) {
                Log::error('SAW Service returned failure', ['result' => $result]);

                $detail = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                return redirect()->back()
                    ->with('error', '[SAW Service] ' . $result['message'])
                    ->with('error_detail', config('app.debug') ? $detail : null);
            }

            $totalTahfiz   = $result['data']['tahfiz']['total_students']   ?? 0;
            $totalLanguage = $result['data']['language']['total_students'] ?? 0;

            return redirect()->route('committee.saw-results.index')
                ->with('success', "Perhitungan SAW berhasil! Tahfiz: {$totalTahfiz} siswa, Language: {$totalLanguage} siswa.");

        } catch (\Throwable $e) {
            return $this->redirectWithException($e, 'Calculate SAW');
        }
    }

    public function determineAcceptance(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tahfizExists  = SawResult::where('academic_year_id', $activeYear->id)->where('specialization', 'tahfiz')->exists();
        $languageExists = SawResult::where('academic_year_id', $activeYear->id)->where('specialization', 'language')->exists();

        if (!$tahfizExists || !$languageExists) {
            return redirect()->back()
                ->with('error', 'Perhitungan SAW belum dilakukan. Silakan hitung SAW terlebih dahulu.');
        }

        try {
            $result = $this->rankingService->determineAcceptanceStatus($activeYear->id);

            Log::info('Determine Acceptance Result', ['result' => $result]);

            if (!$result['success']) {
                Log::error('Ranking Service returned failure', ['result' => $result]);

                $detail = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                return redirect()->back()
                    ->with('error', '[Ranking Service] ' . $result['message'])
                    ->with('error_detail', config('app.debug') ? $detail : null);
            }

            $data    = $result['data'];
            $message = implode(' | ', [
                "Tahfiz: {$data['tahfiz']['accepted']}/{$data['tahfiz']['quota']}",
                "Language: {$data['language']['accepted']}/{$data['language']['quota']}",
                "Regular: {$data['regular']['accepted']}/{$data['regular']['quota']}",
                "Ditolak: {$data['rejected']['total']}",
            ]);

            return redirect()->route('committee.saw-results.index')
                ->with('success', "Status penerimaan berhasil ditentukan! $message");

        } catch (\Throwable $e) {
            return $this->redirectWithException($e, 'Determine Acceptance');
        }
    }

    // SHOW

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