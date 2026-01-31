<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Criteria;
use App\Models\Student;
use App\Models\SawResult;
use App\Models\CriterionWeight;
use App\Models\StudentCriterionValue;
use App\Service\SawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriterionValueController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
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
        
        // Get valid students for the specialization
        $students = Student::with(['criterionValues.criteria', 'user'])
            ->where('academic_year_id', $activeYear->id)
            ->where('specialization', $specialization)
            ->where('validation_status', 'valid')
            ->paginate(20);

        // Get criteria for this specialization
        $criterias = Criteria::forSpecialization($specialization)
            ->active()
            ->ordered()
            ->get();

        // Calculate completion statistics
        $totalStudents = $students->total();
        $completedStudents = 0;
        
        foreach ($students as $student) {
            $valueCount = $student->criterionValues()
                ->whereIn('criteria_id', $criterias->pluck('id'))
                ->count();
            
            if ($valueCount === $criterias->count()) {
                $completedStudents++;
            }
        }

        return view('committee.criterion-values.index', compact(
            'students',
            'criterias',
            'activeYear',
            'specialization',
            'totalStudents',
            'completedStudents'
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

        // Get criteria for student's specialization
        $criterias = Criteria::forSpecialization($student->specialization)
            ->active()
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

        return view('committee.criterion-values.create', compact(
            'student',
            'criterias',
            'existingValues',
            'progress'
        ));
    }

    /**
     * Store criterion values for a student
     */
    public function store(Request $request, Student $student)
    {
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
                // Verify criteria belongs to student's specialization
                $criteria = Criteria::where('id', $criteriaId)
                    ->where('specialization', $student->specialization)
                    ->where('is_active', true)
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

            // Check if all criteria are filled
            $criteriaCount = Criteria::forSpecialization($student->specialization)
                ->active()
                ->count();
            
            $valueCount = StudentCriterionValue::where('student_id', $student->id)
                ->whereHas('criteria', function($query) use ($student) {
                    $query->where('specialization', $student->specialization)
                          ->where('is_active', true);
                })
                ->count();

            if ($valueCount === $criteriaCount) {
                return redirect()->route('committee.criterion-values.index', ['specialization' => $student->specialization])
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
     * Show detail of student's criterion values
     */
    public function show(Student $student)
    {
        $criterias = Criteria::forSpecialization($student->specialization)
            ->active()
            ->ordered()
            ->get();

        $values = StudentCriterionValue::with('criteria')
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('criteria_id');

        return view('committee.criterion-values.show', compact(
            'student',
            'criterias',
            'values'
        ));
    }

    /**
     * Calculate SAW for specific specialization
     */
    /**
 * Calculate SAW for specific specialization
 */
public function calculateSaw(Request $request)
{
    $validated = $request->validate([
        'specialization' => 'required|in:tahfiz,language,regular',
    ]);

    $activeYear = AcademicYear::getActiveYear();
    
    if (!$activeYear) {
        return redirect()->back()
            ->with('error', 'Tidak ada tahun ajaran aktif. Mohon aktifkan tahun ajaran terlebih dahulu.');
    }

    try {
        // Logging untuk debugging
        Log::info('SAW Calculation Started', [
            'specialization' => $validated['specialization'],
            'academic_year_id' => $activeYear->id,
            'academic_year' => $activeYear->year,
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);

        // Pre-validation checks
        $criterias = Criteria::forSpecialization($validated['specialization'])
            ->active()
            ->get();

        if ($criterias->isEmpty()) {
            Log::warning('SAW Calculation Failed: No active criteria', [
                'specialization' => $validated['specialization']
            ]);
            
            return redirect()->back()
                ->with('error', 'Tidak ada kriteria aktif untuk spesialisasi ' . ucfirst($validated['specialization']) . '. Mohon tambahkan kriteria terlebih dahulu.');
        }

        $students = Student::where('academic_year_id', $activeYear->id)
            ->where('specialization', $validated['specialization'])
            ->where('validation_status', 'valid')
            ->get();

        if ($students->isEmpty()) {
            Log::warning('SAW Calculation Failed: No valid students', [
                'specialization' => $validated['specialization'],
                'academic_year_id' => $activeYear->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Tidak ada siswa yang valid untuk spesialisasi ' . ucfirst($validated['specialization']) . '. Mohon validasi siswa terlebih dahulu.');
        }

        // Check if all students have complete criterion values
        $incompleteStudents = [];
        foreach ($students as $student) {
            $valueCount = StudentCriterionValue::where('student_id', $student->id)
                ->whereIn('criteria_id', $criterias->pluck('id'))
                ->count();
            
            if ($valueCount < $criterias->count()) {
                $incompleteStudents[] = [
                    'name' => $student->full_name,
                    'nisn' => $student->nisn,
                    'filled' => $valueCount,
                    'total' => $criterias->count()
                ];
            }
        }

        if (!empty($incompleteStudents)) {
            Log::warning('SAW Calculation Failed: Incomplete criterion values', [
                'specialization' => $validated['specialization'],
                'incomplete_count' => count($incompleteStudents),
                'students' => $incompleteStudents
            ]);
            
            $studentNames = array_slice(array_column($incompleteStudents, 'name'), 0, 3);
            $message = 'Terdapat ' . count($incompleteStudents) . ' siswa dengan nilai kriteria yang belum lengkap: ' . implode(', ', $studentNames);
            if (count($incompleteStudents) > 3) {
                $message .= ', dan ' . (count($incompleteStudents) - 3) . ' siswa lainnya';
            }
            $message .= '. Mohon lengkapi semua nilai kriteria terlebih dahulu.';
            
            return redirect()->back()
                ->with('error', $message);
        }

        // Check if AHP weights exist and are consistent
        $weights = CriterionWeight::with('criteria')
            ->forAcademicYearAndSpecialization($activeYear->id, $validated['specialization'])
            ->consistent()
            ->get();

        if ($weights->isEmpty()) {
            Log::warning('SAW Calculation Failed: No consistent AHP weights', [
                'specialization' => $validated['specialization'],
                'academic_year_id' => $activeYear->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Bobot kriteria dari AHP belum dihitung atau tidak konsisten (CR > 0.1) untuk spesialisasi ' . ucfirst($validated['specialization']) . '. Mohon lakukan perhitungan AHP terlebih dahulu di menu Perbandingan Kriteria.');
        }

        Log::info('SAW Pre-validation Passed', [
            'criteria_count' => $criterias->count(),
            'student_count' => $students->count(),
            'weight_count' => $weights->count()
        ]);

        // Execute SAW calculation
        $result = $this->sawService->calculateScores(
            $activeYear->id,
            $validated['specialization'],
            auth()->id()
        );

        Log::info('SAW Calculation Completed', [
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'message' => $result['message']
        ]);

        if ($result['success']) {
            $totalProcessed = $result['data']['total_students'] ?? 0;
            
            return redirect()->route('committee.criterion-values.index', [
                'specialization' => $validated['specialization']
            ])->with('success', "✅ Perhitungan SAW berhasil! {$totalProcessed} siswa telah diproses dan ranking telah dibuat. Lihat hasilnya di menu Hasil SAW.");
        }

        // If calculation failed
        Log::error('SAW Calculation Service Failed', [
            'result' => $result,
            'specialization' => $validated['specialization']
        ]);

        return redirect()->back()
            ->with('error', 'Gagal menghitung SAW: ' . $result['message']);

    } catch (\Exception $e) {
        Log::error('SAW Calculation Exception', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'specialization' => $validated['specialization'] ?? null,
            'academic_year_id' => $activeYear->id ?? null
        ]);

        $errorMessage = 'Terjadi kesalahan saat menghitung SAW: ' . $e->getMessage();
        
        // Add helpful hints based on error message
        if (str_contains($e->getMessage(), 'weight')) {
            $errorMessage .= ' (Kemungkinan: Bobot kriteria tidak ditemukan atau salah struktur)';
        } elseif (str_contains($e->getMessage(), 'student')) {
            $errorMessage .= ' (Kemungkinan: Data siswa tidak valid atau tidak lengkap)';
        } elseif (str_contains($e->getMessage(), 'criteria')) {
            $errorMessage .= ' (Kemungkinan: Kriteria tidak ditemukan atau tidak aktif)';
        } elseif (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'column')) {
            $errorMessage .= ' (Kemungkinan: Masalah struktur database. Mohon hubungi administrator)';
        }

        return redirect()->back()
            ->with('error', $errorMessage);
    }
}

    /**
     * Bulk input - show form for multiple students
     */
    public function bulkCreate(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $specialization = $request->input('specialization', 'tahfiz');
        $criteriaId = $request->input('criteria_id');

        if (!$criteriaId) {
            return redirect()->route('committee.criterion-values.index')
                ->with('error', 'Pilih kriteria terlebih dahulu');
        }

        $criteria = Criteria::findOrFail($criteriaId);

        // Get students
        $students = Student::with('criterionValues')
            ->where('academic_year_id', $activeYear->id)
            ->where('specialization', $specialization)
            ->where('validation_status', 'valid')
            ->orderBy('full_name')
            ->get();

        return view('committee.criterion-values.bulk-create', compact(
            'students',
            'criteria',
            'activeYear',
            'specialization'
        ));
    }

    /**
     * Store bulk criterion values
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'criteria_id' => 'required|exists:criterias,id',
            'values' => 'required|array',
            'values.*' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $criteria = Criteria::findOrFail($validated['criteria_id']);

            foreach ($validated['values'] as $studentId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $student = Student::find($studentId);
                
                if (!$student || $student->specialization !== $criteria->specialization) {
                    continue;
                }

                StudentCriterionValue::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'criteria_id' => $validated['criteria_id'],
                    ],
                    [
                        'raw_value' => $value,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('committee.criterion-values.index', ['specialization' => $criteria->specialization])
                ->with('success', 'Nilai kriteria berhasil disimpan untuk ' . count(array_filter($validated['values'])) . ' siswa');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk storing criterion values: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan nilai kriteria: ' . $e->getMessage());
        }
    }

    // Tambahkan di CriterionValueController.php atau buat SawResultController baru

    public function sawResultsIndex(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();
        
        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        $specialization = $request->input('specialization', 'tahfiz');
        
        $results = SawResult::with(['student', 'student.user'])
            ->forAcademicYearAndSpecialization($activeYear->id, $specialization)
            ->ranked()
            ->get();

        return view('committee.saw-results.index', compact(
            'results',
            'activeYear',
            'specialization'
        ));
    }

    public function sawResultsShow(SawResult $sawResult)
    {
        $sawResult->load(['student', 'academicYear', 'calculator']);
        
        return view('committee.saw-results.show', compact('sawResult'));
    }
}