<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Http\Requests\ReorderCriteriaRequest;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $criteriasTahfiz = Criteria::forSpecialization('tahfiz')
                ->ordered()
                ->get();

            $criteriasLanguage = Criteria::forSpecialization('language')
                ->ordered()
                ->get();

            return view('admin.criterias.index', compact('criteriasTahfiz', 'criteriasLanguage'));
        } catch (\Exception $e) {
            Log::error('Error loading criterias: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data kriteria');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.criterias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCriteriaRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $criteria = Criteria::create($data);

            DB::commit();

            return redirect()
                ->route('admin.criterias.index')
                ->with('success', 'Kriteria berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating criteria: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Criteria $criteria)
    {
        try {
            $criteria->load(['weights', 'studentValues']);
            
            return view('admin.criterias.show', compact('criteria'));
        } catch (\Exception $e) {
            Log::error('Error showing criteria: ' . $e->getMessage());
            
            return back()->with('error', 'Kriteria tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criteria $criteria)
    {
        return view('admin.criterias.edit', compact('criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCriteriaRequest $request, Criteria $criteria)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $criteria->update($data);

            DB::commit();

            return redirect()
                ->route('admin.criterias.index')
                ->with('success', 'Kriteria berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating criteria: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criteria)
    {
        try {
            // Cek apakah kriteria sudah digunakan dalam perhitungan
            $hasWeights = $criteria->weights()->exists();
            $hasStudentValues = $criteria->studentValues()->exists();
            $hasAhpMatrices = $criteria->ahpMatricesAsRow()->exists() || 
                             $criteria->ahpMatricesAsCol()->exists();

            if ($hasWeights || $hasStudentValues || $hasAhpMatrices) {
                return back()->with('error', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam perhitungan. Nonaktifkan kriteria jika tidak ingin digunakan lagi.');
            }

            DB::beginTransaction();

            $criteria->delete();

            DB::commit();

            return redirect()
                ->route('admin.criterias.index')
                ->with('success', 'Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting criteria: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal menghapus kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif kriteria
     */
    public function toggleStatus(Criteria $criteria)
    {
        try {
            $criteria->update([
                'is_active' => !$criteria->is_active
            ]);

            $status = $criteria->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return back()->with('success', "Kriteria berhasil {$status}");
        } catch (\Exception $e) {
            Log::error('Error toggling criteria status: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal mengubah status kriteria');
        }
    }

    /**
     * Reorder kriteria
     */
    public function reorder(ReorderCriteriaRequest $request, string $specialization)
    {
        try {
            DB::beginTransaction();

            foreach ($request->validated()['orders'] as $id => $order) {
                Criteria::where('id', $id)
                    ->where('specialization', $specialization)
                    ->update(['order' => $order]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Urutan kriteria berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reordering criteria: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan kriteria'
            ], 500);
        }
    }
}