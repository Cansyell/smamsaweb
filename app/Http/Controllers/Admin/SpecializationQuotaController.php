<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecializationQuotaRequest;
use App\Http\Requests\UpdateSpecializationQuotaRequest;
use App\Models\AcademicYear;
use App\Models\SpecializationQuota;
use Illuminate\Http\Request;

class SpecializationQuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SpecializationQuota::with('academicYear')
            ->orderBy('created_at', 'desc');

        // Filter by academic year if provided
        if ($request->has('academic_year_id')) {
            $query->byAcademicYear($request->academic_year_id);
        }

        $quotas = $query->paginate(10);
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('admin.specialization-quotas.index', compact('quotas', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        
        return view('admin.specialization-quotas.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationQuotaRequest $request)
    {
        $validated = $request->validated();

        $quota = SpecializationQuota::createQuota($validated);

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            $quota->activate();
        }

        return redirect()
            ->route('admin.specialization-quotas.index')
            ->with('success', 'Kuota spesialisasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SpecializationQuota $specializationQuota)
    {
        $specializationQuota->load('academicYear');
        
        return view('admin.specialization-quotas.show', compact('specializationQuota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecializationQuota $specializationQuota)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        
        return view('admin.specialization-quotas.edit', compact('specializationQuota', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationQuotaRequest $request, SpecializationQuota $specializationQuota)
    {
        $validated = $request->validated();

        $specializationQuota->updateQuota($validated);

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            $specializationQuota->activate();
        }

        return redirect()
            ->route('admin.specialization-quotas.index')
            ->with('success', 'Kuota spesialisasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecializationQuota $specializationQuota)
    {
        // Cek apakah quota sedang aktif
        if ($specializationQuota->is_active) {
            return redirect()
                ->route('admin.specialization-quotas.index')
                ->with('error', 'Tidak dapat menghapus kuota yang sedang aktif');
        }

        $specializationQuota->delete();

        return redirect()
            ->route('admin.specialization-quotas.index')
            ->with('success', 'Kuota spesialisasi berhasil dihapus');
    }

    /**
     * Toggle status aktif quota
     */
    public function toggleActive(SpecializationQuota $specializationQuota)
    {
        if ($specializationQuota->is_active) {
            $specializationQuota->deactivate();
            $message = 'Kuota spesialisasi dinonaktifkan';
        } else {
            $specializationQuota->activate();
            $message = 'Kuota spesialisasi diaktifkan';
        }

        return redirect()
            ->route('admin.specialization-quotas.index')
            ->with('success', $message);
    }
}