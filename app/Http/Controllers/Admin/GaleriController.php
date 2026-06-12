<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriController extends Controller
{
    private const REDIRECT = 'admin.welcome.setting';

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:150',
            'caption'   => 'nullable|string|max:200',
            'tipe'      => 'required|in:foto,video',
            'gambar'    => 'required_if:tipe,foto|nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'video_url' => 'required_if:tipe,video|nullable|url|max:500',
            'alt_text'  => 'nullable|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'judul.required'     => 'Judul wajib diisi.',
            'tipe.required'      => 'Tipe wajib dipilih.',
            'gambar.required_if' => 'File gambar wajib diupload untuk tipe foto.',
            'gambar.image'       => 'File harus berupa gambar.',
            'gambar.mimes'       => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'gambar.max'         => 'Ukuran gambar maksimal 5MB.',
            'video_url.required_if' => 'URL video wajib diisi untuk tipe video.',
            'video_url.url'      => 'URL video tidak valid.',
            'urutan.required'    => 'Urutan wajib diisi.',
            'urutan.integer'     => 'Urutan harus berupa angka.',
            'urutan.min'         => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'galeri_store')
                ->withInput()
                ->with('open_tab', 'galeri')
                ->with('open_modal', 'modal-galeri-add');
        }

        $data = $request->only(['judul', 'caption', 'tipe', 'video_url', 'alt_text', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->tipe === 'foto' && $request->hasFile('gambar')) {
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        GaleriItem::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil ditambahkan.')
            ->with('open_tab', 'galeri');
    }

    public function update(Request $request, GaleriItem $galeri): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:150',
            'caption'   => 'nullable|string|max:200',
            'tipe'      => 'required|in:foto,video',
            'gambar'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'video_url' => 'required_if:tipe,video|nullable|url|max:500',
            'alt_text'  => 'nullable|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'judul.required'        => 'Judul wajib diisi.',
            'tipe.required'         => 'Tipe wajib dipilih.',
            'gambar.image'          => 'File harus berupa gambar.',
            'gambar.mimes'          => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'gambar.max'            => 'Ukuran gambar maksimal 5MB.',
            'video_url.required_if' => 'URL video wajib diisi untuk tipe video.',
            'video_url.url'         => 'URL video tidak valid.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'urutan.integer'        => 'Urutan harus berupa angka.',
            'urutan.min'            => 'Urutan minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'galeri_update')
                ->withInput()
                ->with('open_tab', 'galeri')
                ->with('open_modal', 'modal-galeri-edit')
                ->with('edit_id', $galeri->id);
        }

        $data = $request->only(['judul', 'caption', 'tipe', 'video_url', 'alt_text', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->tipe === 'foto' && $request->hasFile('gambar')) {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        if ($request->tipe === 'video') {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = null;
        }

        if ($request->tipe === 'foto') {
            $data['video_url'] = null;
        }

        $galeri->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil diperbarui.')
            ->with('open_tab', 'galeri');
    }

    public function destroy(GaleriItem $galeri): RedirectResponse
    {
        if ($galeri->gambar_path) {
            Storage::disk('public')->delete($galeri->gambar_path);
        }
        $galeri->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil dihapus.')
            ->with('open_tab', 'galeri');
    }

    public function toggleActive(GaleriItem $galeri): RedirectResponse
    {
        $galeri->update(['is_active' => ! $galeri->is_active]);

        return back()
            ->with('success', 'Status item galeri diperbarui.')
            ->with('open_tab', 'galeri');
    }

    public function updateUrutan(Request $request): JsonResponse
    {
        $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|integer|exists:galeri_items,id',
            'items.*.urutan' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            GaleriItem::where('id', $item['id'])->update(['urutan' => $item['urutan']]);
        }

        return response()->json(['message' => 'Urutan berhasil diperbarui.']);
    }
}