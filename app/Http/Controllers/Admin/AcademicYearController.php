<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->paginate(10);
        
        return view('admin.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:9|unique:academic_years,year',
            'name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $academicYear = AcademicYear::create($validated);

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            $academicYear->setAsActive();
        }

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        $academicYear->load('specializationQuotas');
        
        return view('admin.academic-years.show', compact('academicYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:9|unique:academic_years,year,' . $academicYear->id,
            'name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $academicYear->update($validated);

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            $academicYear->setAsActive();
        }

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Cek apakah tahun ajaran sedang aktif
        if ($academicYear->is_active) {
            return redirect()
                ->route('admin.academic-years.index')
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif');
        }

        // Cek apakah ada quota yang terkait
        if ($academicYear->specializationQuotas()->count() > 0) {
            return redirect()
                ->route('admin.academic-years.index')
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang memiliki data quota');
        }

        $academicYear->delete();

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus');
    }

    /**
     * Toggle status aktif tahun ajaran
     */
    public function toggleActive(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            $academicYear->update(['is_active' => false]);
            $message = 'Tahun ajaran dinonaktifkan';
        } else {
            $academicYear->setAsActive();
            $message = 'Tahun ajaran diaktifkan';
        }

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', $message);
    }
}