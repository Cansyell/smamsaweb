<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAhpMatrixRequest;
use App\Models\AcademicYear;
use App\Models\AhpMatrix;
use App\Service\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AhpMatrixController extends Controller
{
    public function __construct(
        protected AhpService $ahpService
    ) {}

    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $yearId = $request->academic_year_id ?? $activeYear?->id;
        $specialization = $request->specialization ?? 'tahfiz';

        if (!$yearId) {
            return view('admin.ahp-matrices.index', compact('academicYears'));
        }

        return view('admin.ahp-matrices.index', array_merge(
            $this->ahpService->getMatrixForDisplay($yearId, $specialization),
            [
                'academicYears' => $academicYears,
                'selectedYearId' => $yearId,
                'selectedSpecialization' => $specialization,
                'comparisonScale' => $this->ahpService->getComparisonScale(),
            ]
        ));
    }

    public function store(StoreAhpMatrixRequest $request)
    {
        return $this->ahpService->saveComparison($request->validated())
            ? back()->with('success', 'Perbandingan disimpan')
            : back()->with('error', 'Gagal menyimpan');
    }

    public function calculateWeights(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'specialization' => 'required',
        ]);

        $consistency = $this->ahpService->validateConsistency(
            $request->academic_year_id,
            $request->specialization
        );

        if (!$consistency['valid']) {
            return back()->with('warning', 'CR tidak valid: ' . $consistency['cr']);
        }

        return $this->ahpService->calculateAndSaveWeights(
            $request->academic_year_id,
            $request->specialization
        )
            ? back()->with('success', 'Bobot disimpan')
            : back()->with('error', 'Gagal hitung bobot');
    }

    public function reset(Request $request)
    {
        $this->ahpService->resetMatrix(
            $request->academic_year_id,
            $request->specialization
        );

        return back()->with('success', 'Matrix direset');
    }

    public function show(Request $request)
    {
        $comparison = AhpMatrix::where($request->only([
            'academic_year_id',
            'specialization',
            'criteria_row_id',
            'criteria_col_id'
        ]))->first();

        return response()->json($comparison);
    }
}