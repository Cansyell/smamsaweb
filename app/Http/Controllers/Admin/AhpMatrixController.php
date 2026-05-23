<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAhpMatrixRequest;
use App\Models\AcademicYear;
use App\Models\AhpMatrix;
use App\Models\CriterionWeight;
use App\Service\AhpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AhpMatrixController extends Controller
{
    public function __construct(
        protected AhpService $ahpService
    ) {}

    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $activeYear    = AcademicYear::where('is_active', true)->first();

        $yearId         = (int) ($request->academic_year_id ?? $activeYear?->id);
        $specialization = $request->specialization ?? 'tahfiz';

        if (! $yearId) {
            return view('admin.ahp-matrices.index', [
                'academicYears'          => $academicYears,
                'message'                => 'Belum ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu sebelum menggunakan fitur AHP.',
                'selectedYearId'         => null,
                'selectedSpecialization' => null,
                'comparisonScale'        => $this->ahpService->getComparisonScale(),
            ]);
        }

        $rawData     = $this->ahpService->getMatrixRawData($yearId, $specialization);
        $savedResult = $this->ahpService->getSavedResult($yearId, $specialization);

        // Hitung metrik hanya jika hasil sudah pernah disimpan
        $metrics = null;
        if ($savedResult) {
            $ahpMetrics = AhpMatrix::calculateAhpMetrics($yearId, $specialization);

            if ($ahpMetrics) {
                // Bangun matrix desimal dan colSum untuk Tabel 2
                // matrix[i][j] dibangun dari matrixArray yang sudah tersimpan di DB
                $criterias   = $rawData['criterias'];
                $matrixArray = $rawData['matrixArray'];
                $n           = $criterias->count();

                // Susun matrix 2D berindeks integer (0..n-1)
                $matrix = [];
                $colSum = array_fill(0, $n, 0.0);

                foreach ($criterias as $i => $row) {
                    foreach ($criterias as $j => $col) {
                        if ($row->id === $col->id) {
                            $val = 1.0;
                        } elseif ($row->id < $col->id) {
                            $val = (float) ($matrixArray[$row->id][$col->id] ?? 0);
                        } else {
                            $orig = (float) ($matrixArray[$col->id][$row->id] ?? 0);
                            $val  = $orig > 0 ? 1 / $orig : 0;
                        }
                        $matrix[$i][$j] = $val;
                        $colSum[$j]    += $val;
                    }
                }

                // Gabungkan ke dalam array metrics
                $metrics = array_merge($ahpMetrics, [
                    'matrix' => $matrix,
                    'colSum' => $colSum,
                ]);
            }
        }

        return view('admin.ahp-matrices.index', [
            'academicYears'          => $academicYears,
            'selectedYearId'         => $yearId,
            'selectedSpecialization' => $specialization,
            'comparisonScale'        => $this->ahpService->getComparisonScale(),

            // Data mentah tabel input
            'criterias'              => $rawData['criterias'],
            'matrixArray'            => $rawData['matrixArray'],
            'isComplete'             => $rawData['isComplete'],
            'filledCount'            => $rawData['filledCount'],
            'requiredCount'          => $rawData['requiredCount'],

            // Hasil kalkulasi (null = belum pernah hitung)
            'savedResult'            => $savedResult,
            'metrics'                => $metrics,
        ]);
    }

    public function store(StoreAhpMatrixRequest $request): RedirectResponse
    {
        return $this->ahpService->saveComparison($request->validated())
            ? back()->with('success', 'Nilai perbandingan berhasil disimpan.')
            : back()->with('error', 'Gagal menyimpan nilai perbandingan.');
    }

    public function calculateWeights(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'specialization'   => 'required|in:tahfiz,language',
        ]);

        $yearId         = (int) $request->academic_year_id;
        $specialization = $request->specialization;

        $result = $this->ahpService->calculateAndSaveWeights($yearId, $specialization);

        if ($result === false) {
            return back()->with('error', 'Gagal menghitung bobot. Periksa log untuk detail.');
        }

        if (is_array($result) && isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', 'Bobot prioritas berhasil dihitung dan disimpan.');
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'specialization'   => 'required|in:tahfiz,language',
        ]);

        $this->ahpService->resetMatrix(
            (int) $request->academic_year_id,
            $request->specialization
        );

        return back()->with('success', 'Matriks perbandingan berhasil direset.');
    }

    public function show(Request $request)
    {
        $comparison = AhpMatrix::where($request->only([
            'academic_year_id',
            'specialization',
            'criteria_row_id',
            'criteria_col_id',
        ]))->first();

        return response()->json($comparison);
    }
}