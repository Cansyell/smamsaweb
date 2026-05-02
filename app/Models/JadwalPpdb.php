<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPpdb extends Model
{
    protected $table = 'jadwal_ppdb';

    protected $fillable = [
        'tahun_ajaran',
        'nomor_urut',
        'tanggal_label',
        'tanggal_mulai',
        'tanggal_selesai',
        'judul',
        'deskripsi',
        'status',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
        'nomor_urut'      => 'integer',
    ];

    /**
     * Auto-update status berdasarkan tanggal hari ini.
     */
    public function syncStatus(): self
    {
        $today = now()->toDateString();

        if ($today < $this->tanggal_mulai->toDateString()) {
            $this->status = 'upcoming';
        } elseif ($today > $this->tanggal_selesai->toDateString()) {
            $this->status = 'done';
        } else {
            $this->status = 'active';
        }

        return $this;
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('nomor_urut');
    }

    public function scopeTahunAjaran($query, string $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }
}