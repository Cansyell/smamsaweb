<?php

namespace App\Http\Controllers;

use App\Models\GaleriItem;
use App\Models\HeroSection;
use App\Models\JadwalPpdb;
use App\Models\PpdbBiaya;
use App\Models\PpdbPersyaratan;
use App\Models\PpdbSetting;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Halaman publik Welcome Page.
     */
    public function index(): View
    {
        $hero        = HeroSection::getActive();
        $galeri      = GaleriItem::aktif()->get();
        $ppdbSetting = PpdbSetting::getActive();

        $jadwals = JadwalPpdb::aktif()
            ->when($ppdbSetting, fn ($q) => $q->tahunAjaran($ppdbSetting->tahun_ajaran))
            ->get();

        $persyaratan = PpdbPersyaratan::aktif()
            ->when($ppdbSetting, fn ($q) => $q->where('tahun_ajaran', $ppdbSetting->tahun_ajaran))
            ->get();

        $biaya = PpdbBiaya::aktif()
            ->when($ppdbSetting, fn ($q) => $q->where('tahun_ajaran', $ppdbSetting->tahun_ajaran))
            ->get();

        return view('welcome', compact(
            'hero',
            'galeri',
            'ppdbSetting',
            'jadwals',
            'persyaratan',
            'biaya',
        ));
    }
}