<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CriterionWeight;
use App\Models\AhpMatrix;
use Illuminate\Http\Request;

class AhpResultController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $selectedYearId = $request->academic_year_id ?? $activeYear?->id;
        $selectedSpecialization = $request->specialization ?? 'tahfiz';

        if (!$selectedYearId) {
            return view('admin.ahp-results.index', [
                'academicYears' => $academicYears,
                'message' => 'Belum ada tahun akademik yang tersedia. Silakan buat tahun akademik terlebih dahulu.',
            ]);
        }

        // Get weights with criteria relation
        $weights = CriterionWeight::with(['criteria', 'calculator', 'academicYear'])
            ->forAcademicYearAndSpecialization($selectedYearId, $selectedSpecialization)
            ->get();

        // Get consistency ratio from any weight record (should be same for all criteria in same calculation)
        $consistencyRatio = $weights->first()?->consistency_ratio;
        $isConsistent = $weights->first()?->is_consistent ?? false;
        $calculatedAt = $weights->first()?->calculated_at;
        $calculatedBy = $weights->first()?->calculator;

        // Get matrix data for additional info
        $matrixData = AhpMatrix::getMatrixData($selectedYearId, $selectedSpecialization);
        $criterias = $matrixData['criterias'];
        $isMatrixComplete = AhpMatrix::isMatrixComplete($selectedYearId, $selectedSpecialization);

        // Calculate total weight (should be 1.0)
        $totalWeight = $weights->sum('weight');

        return view('admin.ahp-results.index', [
            'academicYears' => $academicYears,
            'selectedYearId' => $selectedYearId,
            'selectedSpecialization' => $selectedSpecialization,
            'weights' => $weights,
            'consistencyRatio' => $consistencyRatio,
            'isConsistent' => $isConsistent,
            'calculatedAt' => $calculatedAt,
            'calculatedBy' => $calculatedBy,
            'totalWeight' => $totalWeight,
            'criteriaCount' => $criterias->count(),
            'isMatrixComplete' => $isMatrixComplete,
        ]);
    }
}