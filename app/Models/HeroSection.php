<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeroSection extends Model
{
    protected $fillable = [
        'badge_text',
        'title_main',
        'title_italic',
        'subtitle',
        'btn_primary_label',
        'btn_primary_url',
        'btn_outline_label',
        'btn_outline_url',
        'background_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stats(): HasMany
    {
        return $this->hasMany(HeroStat::class)->orderBy('urutan');
    }

    /**
     * Ambil hero section yang aktif (hanya satu yang aktif sekaligus).
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->with('stats')->first();
    }
}