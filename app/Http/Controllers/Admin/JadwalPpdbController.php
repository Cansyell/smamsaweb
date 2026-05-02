<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPpdb;
use App\Models\PpdbBiaya;
use App\Models\PpdbPersyaratan;
use App\Models\PpdbSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JadwalPpdbController extends Controller
{
    private const REDIRECT = 'admin.welcome.setting';

    // ─────────────────────────────────────────
    // JADWAL
    // ─────────────────────────────────────────

    public function storeJadwal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tahun_ajaran'    => 'required|string|max:20',
            'nomor_urut'      => 'required|integer|min:1',
            'tanggal_label'   => 'required|string|max:80',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'judul'           => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:upcoming,active,done',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        JadwalPpdb::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, JadwalPpdb $jadwal): RedirectResponse
    {
        $data = $request->validate([
            'tahun_ajaran'    => 'required|string|max:20',
            'nomor_urut'      => 'required|integer|min:1',
            'tanggal_label'   => 'required|string|max:80',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'judul'           => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:upcoming,active,done',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        dd($data);
        $jadwal->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil diperbarui.');
    }

    public function destroyJadwal(JadwalPpdb $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil dihapus.');
    }

    /**
     * Auto-sync semua status jadwal berdasarkan tanggal hari ini.
     */
    public function syncStatusJadwal(): RedirectResponse
    {
        JadwalPpdb::all()->each(fn ($j) => $j->syncStatus()->save());

        return back()->with('success', 'Status semua jadwal berhasil disinkronkan.');
    }

    // ─────────────────────────────────────────
    // PPDB SETTINGS
    // ─────────────────────────────────────────

    public function storeSetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tahun_ajaran'     => 'required|string|max:20',
            'telepon'          => 'nullable|string|max:30',
            'jam_operasional'  => 'nullable|string|max:100',
            'tanggal_buka'     => 'nullable|date',
            'tanggal_tutup'    => 'nullable|date|after_or_equal:tanggal_buka',
            'catatan_beasiswa' => 'nullable|string|max:300',
            'link_pendaftaran' => 'nullable|url|max:300',
            'is_active'        => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            PpdbSetting::where('is_active', true)->update(['is_active' => false]);
        }

        $data['is_active'] = $request->boolean('is_active');
        PpdbSetting::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Setting PPDB berhasil disimpan.');
    }

    public function updateSetting(Request $request, PpdbSetting $setting): RedirectResponse
    {
        $data = $request->validate([
            'tahun_ajaran'     => 'required|string|max:20',
            'telepon'          => 'nullable|string|max:30',
            'jam_operasional'  => 'nullable|string|max:100',
            'tanggal_buka'     => 'nullable|date',
            'tanggal_tutup'    => 'nullable|date|after_or_equal:tanggal_buka',
            'catatan_beasiswa' => 'nullable|string|max:300',
            'link_pendaftaran' => 'nullable|url|max:300',
            'is_active'        => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            PpdbSetting::where('id', '!=', $setting->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $data['is_active'] = $request->boolean('is_active');
        $setting->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Setting PPDB berhasil diperbarui.');
    }

    // ─────────────────────────────────────────
    // BIAYA
    // ─────────────────────────────────────────

    public function storeBiaya(Request $request): RedirectResponse
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'nama_biaya'   => 'required|string|max:150',
            'nominal'      => 'required|integer|min:0',
            'keterangan'   => 'nullable|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        PpdbBiaya::create([
            ...$request->only(['tahun_ajaran', 'nama_biaya', 'nominal', 'keterangan', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil ditambahkan.');
    }

    public function updateBiaya(Request $request, PpdbBiaya $biaya): RedirectResponse
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'nama_biaya'   => 'required|string|max:150',
            'nominal'      => 'required|integer|min:0',
            'keterangan'   => 'nullable|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $biaya->update([
            ...$request->only(['tahun_ajaran', 'nama_biaya', 'nominal', 'keterangan', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil diperbarui.');
    }

    public function destroyBiaya(PpdbBiaya $biaya): RedirectResponse
    {
        $biaya->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil dihapus.');
    }

    // ─────────────────────────────────────────
    // PERSYARATAN
    // ─────────────────────────────────────────

    public function storePersyaratan(Request $request): RedirectResponse
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'dokumen'      => 'required|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        PpdbPersyaratan::create([
            ...$request->only(['tahun_ajaran', 'dokumen', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil ditambahkan.');
    }

    public function updatePersyaratan(Request $request, PpdbPersyaratan $persyaratan): RedirectResponse
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'dokumen'      => 'required|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $persyaratan->update([
            ...$request->only(['tahun_ajaran', 'dokumen', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil diperbarui.');
    }

    public function destroyPersyaratan(PpdbPersyaratan $persyaratan): RedirectResponse
    {
        $persyaratan->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil dihapus.');
    }
}