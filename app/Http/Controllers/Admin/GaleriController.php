<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    private const REDIRECT = 'admin.welcome.setting';

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul'     => 'required|string|max:150',
            'caption'   => 'nullable|string|max:200',
            'tipe'      => 'required|in:foto,video',
            'gambar'    => 'required_if:tipe,foto|nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'video_url' => 'required_if:tipe,video|nullable|url|max:500',
            'alt_text'  => 'nullable|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['judul', 'caption', 'tipe', 'video_url', 'alt_text', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->tipe === 'foto' && $request->hasFile('gambar')) {
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        GaleriItem::create($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function update(Request $request, GaleriItem $galeri): RedirectResponse
    {
        $request->validate([
            'judul'     => 'required|string|max:150',
            'caption'   => 'nullable|string|max:200',
            'tipe'      => 'required|in:foto,video',
            'gambar'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'video_url' => 'required_if:tipe,video|nullable|url|max:500',
            'alt_text'  => 'nullable|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['judul', 'caption', 'tipe', 'video_url', 'alt_text', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->tipe === 'foto' && $request->hasFile('gambar')) {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = $request->file('gambar')->store('galeri', 'public');
        }

        // Bersihkan gambar_path jika berubah ke video
        if ($request->tipe === 'video') {
            if ($galeri->gambar_path) {
                Storage::disk('public')->delete($galeri->gambar_path);
            }
            $data['gambar_path'] = null;
        }

        // Bersihkan video_url jika berubah ke foto
        if ($request->tipe === 'foto') {
            $data['video_url'] = null;
        }

        $galeri->update($data);

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function destroy(GaleriItem $galeri): RedirectResponse
    {
        if ($galeri->gambar_path) {
            Storage::disk('public')->delete($galeri->gambar_path);
        }
        $galeri->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Item galeri berhasil dihapus.');
    }

    /**
     * Toggle aktif/non-aktif via PATCH (form kecil di tabel).
     */
    public function toggleActive(GaleriItem $galeri): RedirectResponse
    {
        $galeri->update(['is_active' => ! $galeri->is_active]);

        return back()->with('success', 'Status item galeri diperbarui.');
    }

    /**
     * Update urutan via drag-and-drop (AJAX).
     * Body: { "items": [{"id": 1, "urutan": 0}, ...] }
     */
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