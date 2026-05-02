<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    private const REDIRECT = 'admin.welcome.setting';

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'badge_text'        => 'required|string|max:150',
            'title_main'        => 'required|string|max:150',
            'title_italic'      => 'required|string|max:150',
            'subtitle'          => 'required|string',
            'btn_primary_label' => 'required|string|max:80',
            'btn_primary_url'   => 'required|string|max:200',
            'btn_outline_label' => 'required|string|max:80',
            'btn_outline_url'   => 'required|string|max:200',
            'background_image'  => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'is_active'         => 'boolean',
            'stats'             => 'nullable|array',
            'stats.*.number'    => 'required_with:stats|string|max:20',
            'stats.*.label'     => 'required_with:stats|string|max:80',
            'stats.*.urutan'    => 'required_with:stats|integer|min:0',
        ]);

        if ($request->boolean('is_active')) {
            HeroSection::where('is_active', true)->update(['is_active' => false]);
        }

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')
                ->store('hero', 'public');
        }

        $hero = HeroSection::create($data);

        if (! empty($data['stats'])) {
            foreach ($data['stats'] as $stat) {
                $hero->stats()->create($stat);
            }
        }

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Hero section berhasil ditambahkan.');
    }

    public function update(Request $request, HeroSection $hero): RedirectResponse
    {
        $data = $request->validate([
            'badge_text'        => 'required|string|max:150',
            'title_main'        => 'required|string|max:150',
            'title_italic'      => 'required|string|max:150',
            'subtitle'          => 'required|string',
            'btn_primary_label' => 'required|string|max:80',
            'btn_primary_url'   => 'required|string|max:200',
            'btn_outline_label' => 'required|string|max:80',
            'btn_outline_url'   => 'required|string|max:200',
            'background_image'  => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'remove_background_image' => 'nullable|boolean',
            'is_active'         => 'boolean',
            'stats'             => 'nullable|array',
            'stats.*.id'        => 'nullable|integer|exists:hero_stats,id',
            'stats.*.number'    => 'required_with:stats|string|max:20',
            'stats.*.label'     => 'required_with:stats|string|max:80',
            'stats.*.urutan'    => 'required_with:stats|integer|min:0',
        ]);

        if ($request->boolean('is_active')) {
            HeroSection::where('id', '!=', $hero->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        // Hapus gambar jika diminta
        if ($request->boolean('remove_background_image') && $hero->background_image) {
            Storage::disk('public')->delete($hero->background_image);
            $data['background_image'] = null;
        }

        // Ganti gambar jika ada upload baru
        if ($request->hasFile('background_image')) {
            if ($hero->background_image) {
                Storage::disk('public')->delete($hero->background_image);
            }
            $data['background_image'] = $request->file('background_image')
                ->store('hero', 'public');
        }

        $hero->update($data);

        // Sync stats: hapus lama, re-insert
        $hero->stats()->delete();
        if (! empty($data['stats'])) {
            foreach ($data['stats'] as $stat) {
                $hero->stats()->create([
                    'number' => $stat['number'],
                    'label'  => $stat['label'],
                    'urutan' => $stat['urutan'],
                ]);
            }
        }

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Hero section berhasil diperbarui.');
    }

    public function destroy(HeroSection $hero): RedirectResponse
    {
        if ($hero->background_image) {
            Storage::disk('public')->delete($hero->background_image);
        }
        $hero->delete();

        return redirect()->route(self::REDIRECT)
            ->with('success', 'Hero section berhasil dihapus.');
    }

    public function toggleActive(HeroSection $hero): RedirectResponse
    {
        if (! $hero->is_active) {
            HeroSection::where('is_active', true)->update(['is_active' => false]);
            $hero->update(['is_active' => true]);
        } else {
            $hero->update(['is_active' => false]);
        }

        return back()->with('success', 'Status hero section diperbarui.');
    }
}