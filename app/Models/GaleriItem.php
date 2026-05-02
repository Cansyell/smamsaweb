<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GaleriItem extends Model
{
    protected $fillable = [
        'judul',
        'caption',
        'tipe',
        'gambar_path',
        'video_url',
        'alt_text',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    protected $appends = ['gambar_url'];

    /**
     * URL publik gambar (hanya untuk tipe foto).
     */
    public function getGambarUrlAttribute(): ?string
    {
        if ($this->tipe === 'foto' && $this->gambar_path) {
            return Storage::url($this->gambar_path);
        }
        return null;
    }

    /**
     * Transformasi URL YouTube biasa → URL embed.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->tipe !== 'video' || ! $this->video_url) {
            return null;
        }

        // Sudah dalam format embed
        if (str_contains($this->video_url, 'youtube.com/embed/')) {
            return $this->video_url;
        }

        // Format: https://youtu.be/ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Format: https://www.youtube.com/watch?v=ID
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        return $this->video_url;
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}