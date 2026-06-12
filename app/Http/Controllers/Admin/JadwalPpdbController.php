<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPpdb;
use App\Models\PpdbBiaya;
use App\Models\PpdbPersyaratan;
use App\Models\PpdbSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPpdbController extends Controller
{
    private const REDIRECT = 'admin.welcome.setting';

    // =========================================================================
    // JADWAL
    // =========================================================================

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran'    => 'required|string|max:20',
            'nomor_urut'      => 'required|integer|min:1',
            'tanggal_label'   => 'required|string|max:80',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'judul'           => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:upcoming,active,done',
            'is_active'       => 'boolean',
        ], [
            'tahun_ajaran.required'    => 'Tahun ajaran wajib diisi.',
            'nomor_urut.required'      => 'Nomor urut wajib diisi.',
            'nomor_urut.min'           => 'Nomor urut minimal 1.',
            'tanggal_label.required'   => 'Label tanggal wajib diisi.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'       => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'     => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'judul.required'           => 'Judul tahapan wajib diisi.',
            'status.required'          => 'Status wajib dipilih.',
            'status.in'                => 'Status tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'jadwal_store')
                ->withInput()
                ->with('open_tab', 'jadwal')
                ->with('open_modal', 'modal-jadwal-add');
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        JadwalPpdb::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil ditambahkan.')
            ->with('open_tab', 'jadwal');
    }

    public function updateJadwal(Request $request, JadwalPpdb $jadwal): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran'    => 'required|string|max:20',
            'nomor_urut'      => 'required|integer|min:1',
            'tanggal_label'   => 'required|string|max:80',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'judul'           => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:upcoming,active,done',
            'is_active'       => 'boolean',
        ], [
            'tahun_ajaran.required'    => 'Tahun ajaran wajib diisi.',
            'nomor_urut.required'      => 'Nomor urut wajib diisi.',
            'nomor_urut.min'           => 'Nomor urut minimal 1.',
            'tanggal_label.required'   => 'Label tanggal wajib diisi.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'       => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'     => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'judul.required'           => 'Judul tahapan wajib diisi.',
            'status.required'          => 'Status wajib dipilih.',
            'status.in'                => 'Status tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'jadwal_update')
                ->withInput()
                ->with('open_tab', 'jadwal')
                ->with('open_modal', 'modal-jadwal-edit')
                ->with('edit_id', $jadwal->id);
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        $jadwal->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil diperbarui.')
            ->with('open_tab', 'jadwal');
    }

    public function destroyJadwal(JadwalPpdb $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Jadwal PPDB berhasil dihapus.')
            ->with('open_tab', 'jadwal');
    }

    public function syncStatusJadwal(): RedirectResponse
    {
        JadwalPpdb::all()->each(fn ($j) => $j->syncStatus()->save());

        return back()
            ->with('success', 'Status semua jadwal berhasil disinkronkan.')
            ->with('open_tab', 'jadwal');
    }

    // =========================================================================
    // PPDB SETTINGS
    // =========================================================================

    public function storeSetting(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran'     => 'required|string|max:20',
            'telepon'          => 'nullable|string|max:30',
            'jam_operasional'  => 'nullable|string|max:100',
            'tanggal_buka'     => 'nullable|date',
            'tanggal_tutup'    => 'nullable|date|after_or_equal:tanggal_buka',
            'catatan_beasiswa' => 'nullable|string|max:300',
            'link_pendaftaran' => 'nullable|url|max:300',
            'is_active'        => 'boolean',
        ], [
            'tahun_ajaran.required'          => 'Tahun ajaran wajib diisi.',
            'tanggal_buka.date'              => 'Format tanggal buka tidak valid.',
            'tanggal_tutup.date'             => 'Format tanggal tutup tidak valid.',
            'tanggal_tutup.after_or_equal'   => 'Tanggal tutup harus sama atau setelah tanggal buka.',
            'link_pendaftaran.url'           => 'Format link pendaftaran tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'setting_store')
                ->withInput()
                ->with('open_tab', 'kontak');
        }

        if ($request->boolean('is_active')) {
            PpdbSetting::where('is_active', true)->update(['is_active' => false]);
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');
        PpdbSetting::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Setting PPDB berhasil disimpan.')
            ->with('open_tab', 'kontak');
    }

    public function updateSetting(Request $request, PpdbSetting $setting): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran'     => 'required|string|max:20',
            'telepon'          => 'nullable|string|max:30',
            'jam_operasional'  => 'nullable|string|max:100',
            'tanggal_buka'     => 'nullable|date',
            'tanggal_tutup'    => 'nullable|date|after_or_equal:tanggal_buka',
            'catatan_beasiswa' => 'nullable|string|max:300',
            'link_pendaftaran' => 'nullable|url|max:300',
            'is_active'        => 'boolean',
        ], [
            'tahun_ajaran.required'          => 'Tahun ajaran wajib diisi.',
            'tanggal_buka.date'              => 'Format tanggal buka tidak valid.',
            'tanggal_tutup.date'             => 'Format tanggal tutup tidak valid.',
            'tanggal_tutup.after_or_equal'   => 'Tanggal tutup harus sama atau setelah tanggal buka.',
            'link_pendaftaran.url'           => 'Format link pendaftaran tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'setting_update')
                ->withInput()
                ->with('open_tab', 'kontak');
        }

        if ($request->boolean('is_active')) {
            PpdbSetting::where('id', '!=', $setting->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');
        $setting->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Setting PPDB berhasil diperbarui.')
            ->with('open_tab', 'kontak');
    }

    // =========================================================================
    // BIAYA
    // =========================================================================

    public function storeBiaya(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required|string|max:20',
            'nama_biaya'   => 'required|string|max:150',
            'nominal'      => 'required|integer|min:0',
            'keterangan'   => 'nullable|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'nama_biaya.required'   => 'Nama biaya wajib diisi.',
            'nominal.required'      => 'Nominal wajib diisi.',
            'nominal.integer'       => 'Nominal harus berupa angka.',
            'nominal.min'           => 'Nominal tidak boleh negatif.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'urutan.min'            => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'biaya_store')
                ->withInput()
                ->with('open_tab', 'biaya')
                ->with('open_modal', 'modal-biaya-add');
        }

        PpdbBiaya::create([
            ...$request->only(['tahun_ajaran', 'nama_biaya', 'nominal', 'keterangan', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil ditambahkan.')
            ->with('open_tab', 'biaya');
    }

    public function updateBiaya(Request $request, PpdbBiaya $biaya): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required|string|max:20',
            'nama_biaya'   => 'required|string|max:150',
            'nominal'      => 'required|integer|min:0',
            'keterangan'   => 'nullable|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'nama_biaya.required'   => 'Nama biaya wajib diisi.',
            'nominal.required'      => 'Nominal wajib diisi.',
            'nominal.integer'       => 'Nominal harus berupa angka.',
            'nominal.min'           => 'Nominal tidak boleh negatif.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'urutan.min'            => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'biaya_update')
                ->withInput()
                ->with('open_tab', 'biaya')
                ->with('open_modal', 'modal-biaya-edit')
                ->with('edit_id', $biaya->id);
        }

        $biaya->update([
            ...$request->only(['tahun_ajaran', 'nama_biaya', 'nominal', 'keterangan', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil diperbarui.')
            ->with('open_tab', 'biaya');
    }

    public function destroyBiaya(PpdbBiaya $biaya): RedirectResponse
    {
        $biaya->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Biaya PPDB berhasil dihapus.')
            ->with('open_tab', 'biaya');
    }

    // =========================================================================
    // PERSYARATAN
    // =========================================================================

    public function storePersyaratan(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required|string|max:20',
            'dokumen'      => 'required|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'dokumen.required'      => 'Nama dokumen wajib diisi.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'urutan.min'            => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'persyaratan_store')
                ->withInput()
                ->with('open_tab', 'persyaratan')
                ->with('open_modal', 'modal-persyaratan-add');
        }

        PpdbPersyaratan::create([
            ...$request->only(['tahun_ajaran', 'dokumen', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil ditambahkan.')
            ->with('open_tab', 'persyaratan');
    }

    public function updatePersyaratan(Request $request, PpdbPersyaratan $persyaratan): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran' => 'required|string|max:20',
            'dokumen'      => 'required|string|max:200',
            'urutan'       => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'dokumen.required'      => 'Nama dokumen wajib diisi.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'urutan.min'            => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'persyaratan_update')
                ->withInput()
                ->with('open_tab', 'persyaratan')
                ->with('open_modal', 'modal-persyaratan-edit')
                ->with('edit_id', $persyaratan->id);
        }

        $persyaratan->update([
            ...$request->only(['tahun_ajaran', 'dokumen', 'urutan']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil diperbarui.')
            ->with('open_tab', 'persyaratan');
    }

    public function destroyPersyaratan(PpdbPersyaratan $persyaratan): RedirectResponse
    {
        $persyaratan->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Persyaratan berhasil dihapus.')
            ->with('open_tab', 'persyaratan');
    }
}