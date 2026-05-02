<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbSetting extends Model
{
    protected $table = 'ppdb_settings';

    protected $fillable = [
        'tahun_ajaran',
        'telepon',
        'jam_operasional',
        'tanggal_buka',
        'tanggal_tutup',
        'catatan_beasiswa',
        'link_pendaftaran',
        'is_active',
    ];

    protected $casts = [
        'tanggal_buka'   => 'date',
        'tanggal_tutup'  => 'date',
        'is_active'      => 'boolean',
    ];

    public static function getActive(): ?self
    {
        return self::where('is_active', true)->latest()->first();
    }

    /**
     * Apakah pendaftaran masih dibuka hari ini?
     */
    public function isPendaftaranBuka(): bool
    {
        $today = now()->toDateString();
        return $today >= $this->tanggal_buka?->toDateString()
            && $today <= $this->tanggal_tutup?->toDateString();
    }
}