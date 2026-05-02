<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriItem;
use App\Models\HeroSection;
use App\Models\JadwalPpdb;
use App\Models\PpdbBiaya;
use App\Models\PpdbPersyaratan;
use App\Models\PpdbSetting;
use Illuminate\View\View;

class WelcomeSettingController extends Controller
{
    /**
     * Halaman admin pengaturan welcome page (tab view).
     * CRUD masing-masing section ditangani oleh controller-nya sendiri:
     *   - Hero    → HeroSectionController
     *   - Galeri  → GaleriController
     *   - PPDB    → JadwalPpdbController
     */
    public function index(): View
    {
        $hero        = HeroSection::with('stats')->latest()->first();
        $galeri      = GaleriItem::orderBy('urutan')->orderBy('id')->get();
        $ppdbSetting = PpdbSetting::getActive();

        $tahunAjaran = $ppdbSetting?->tahun_ajaran;

        $jadwals = JadwalPpdb::orderBy('nomor_urut')
            ->when($tahunAjaran, fn ($q) => $q->tahunAjaran($tahunAjaran))
            ->get();

        $persyaratan = PpdbPersyaratan::orderBy('urutan')
            ->when($tahunAjaran, fn ($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->get();

        $biaya = PpdbBiaya::orderBy('urutan')
            ->when($tahunAjaran, fn ($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->get();

        return view('admin.welcome.settings', compact(
            'hero',
            'galeri',
            'ppdbSetting',
            'jadwals',
            'persyaratan',
            'biaya',
        ));
    }
}