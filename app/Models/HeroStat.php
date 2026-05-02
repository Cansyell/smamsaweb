<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroStat extends Model
{
    protected $fillable = [
        'hero_section_id',
        'number',
        'label',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function heroSection(): BelongsTo
    {
        return $this->belongsTo(HeroSection::class);
    }
}