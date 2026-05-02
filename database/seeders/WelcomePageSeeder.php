<?php

namespace Database\Seeders;

use App\Models\GaleriItem;
use App\Models\HeroSection;
use App\Models\JadwalPpdb;
use App\Models\PpdbBiaya;
use App\Models\PpdbPersyaratan;
use App\Models\PpdbSetting;
use Illuminate\Database\Seeder;

class WelcomePageSeeder extends Seeder
{
    public function run(): void
    {
        // ── HERO SECTION ──────────────────────────────────
        $hero = HeroSection::create([
            'badge_text'        => 'PPDB Tahun Ajaran 2025/2026',
            'title_main'        => 'Wujudkan Mimpi',
            'title_italic'      => 'Bersama Kami',
            'subtitle'          => 'SMA Muhammadiyah 1 Purwokerto membuka penerimaan murid baru untuk tahun ajaran 2025/2026. Bergabunglah dengan ribuan alumni berprestasi yang telah mengukir jejak di tingkat nasional dan internasional.',
            'btn_primary_label' => 'Info Pendaftaran',
            'btn_primary_url'   => '#ppdb',
            'btn_outline_label' => 'Kenali Sekolah Kami',
            'btn_outline_url'   => '#visi-misi',
            'background_image'  => null,
            'is_active'         => true,
        ]);

        $hero->stats()->createMany([
            ['number' => '3.200+', 'label' => 'Alumni Berprestasi', 'urutan' => 1],
            ['number' => '98%',    'label' => 'Lulus PTN',          'urutan' => 2],
            ['number' => '40+',    'label' => 'Ekstrakurikuler',    'urutan' => 3],
        ]);

        // ── GALERI ────────────────────────────────────────
        GaleriItem::insert([
            [
                'judul'       => 'Kegiatan Sekolah 1',
                'caption'     => 'Kegiatan Sekolah',
                'tipe'        => 'foto',
                'gambar_path' => 'images/image1-smamsa.jpeg',
                'video_url'   => null,
                'alt_text'    => 'Galeri 1',
                'urutan'      => 1,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Kegiatan Sekolah 2',
                'caption'     => 'Kegiatan Sekolah',
                'tipe'        => 'foto',
                'gambar_path' => 'images/image2-smamsa.jpeg',
                'video_url'   => null,
                'alt_text'    => 'Galeri 2',
                'urutan'      => 2,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Kegiatan Sekolah 3',
                'caption'     => 'Kegiatan Sekolah',
                'tipe'        => 'foto',
                'gambar_path' => 'images/image3-smamsa.jpeg',
                'video_url'   => null,
                'alt_text'    => 'Galeri 3',
                'urutan'      => 3,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Video Profil Sekolah',
                'caption'     => 'Video Profil Sekolah',
                'tipe'        => 'video',
                'gambar_path' => null,
                'video_url'   => 'https://www.youtube.com/embed/JTatuz7sy5k',
                'alt_text'    => 'Video Profil',
                'urutan'      => 4,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // ── JADWAL PPDB ───────────────────────────────────
        $jadwals = [
            [
                'nomor_urut'      => 1,
                'tanggal_label'   => '1 – 15 Juni 2025',
                'tanggal_mulai'   => '2025-06-01',
                'tanggal_selesai' => '2025-06-15',
                'judul'           => 'Pembukaan Pendaftaran Online',
                'deskripsi'       => 'Registrasi akun dan pengisian formulir pendaftaran melalui portal resmi PPDB.',
            ],
            [
                'nomor_urut'      => 2,
                'tanggal_label'   => '16 – 25 Juni 2025',
                'tanggal_mulai'   => '2025-06-16',
                'tanggal_selesai' => '2025-06-25',
                'judul'           => 'Pengumpulan Berkas',
                'deskripsi'       => 'Unggah dokumen persyaratan yang diperlukan melalui sistem pendaftaran online.',
            ],
            [
                'nomor_urut'      => 3,
                'tanggal_label'   => '1 – 10 Juli 2025',
                'tanggal_mulai'   => '2025-07-01',
                'tanggal_selesai' => '2025-07-10',
                'judul'           => 'Tes Seleksi & Wawancara',
                'deskripsi'       => 'Tes tertulis (Matematika, IPA, IPS, B.Indonesia) dan sesi wawancara dengan panitia.',
            ],
            [
                'nomor_urut'      => 4,
                'tanggal_label'   => '20 Juli 2025',
                'tanggal_mulai'   => '2025-07-20',
                'tanggal_selesai' => '2025-07-20',
                'judul'           => 'Pengumuman Hasil Seleksi',
                'deskripsi'       => 'Hasil seleksi diumumkan melalui portal resmi dan akan dikirim via email terdaftar.',
            ],
            [
                'nomor_urut'      => 5,
                'tanggal_label'   => '21 – 30 Juli 2025',
                'tanggal_mulai'   => '2025-07-21',
                'tanggal_selesai' => '2025-07-30',
                'judul'           => 'Daftar Ulang & Orientasi',
                'deskripsi'       => 'Konfirmasi kehadiran, pembayaran, dan persiapan masa orientasi siswa baru.',
            ],
        ];

        foreach ($jadwals as $j) {
            $jadwal = new JadwalPpdb(array_merge($j, ['tahun_ajaran' => '2025/2026', 'is_active' => true]));
            JadwalPpdb::create(array_merge($j, [
                'tahun_ajaran' => '2025/2026',
                'status'       => 'upcoming',
                'is_active'    => true,
            ]));
        }

        // ── PPDB SETTING ──────────────────────────────────
        PpdbSetting::create([
            'tahun_ajaran'     => '2025/2026',
            'telepon'          => '(0281) 633373',
            'jam_operasional'  => 'Senin–Sabtu, 08.00–12.30 WIB',
            'tanggal_buka'     => '2025-06-01',
            'tanggal_tutup'    => '2025-07-30',
            'catatan_beasiswa' => '*Tersedia beasiswa bagi siswa berprestasi dan kurang mampu',
            'link_pendaftaran' => '/register',
            'is_active'        => true,
        ]);

        // ── PERSYARATAN ───────────────────────────────────
        $persyaratans = [
            'Fotokopi Ijazah / Surat Keterangan Lulus SMP',
            'Fotokopi SKHUN (Surat Keterangan Hasil Ujian Nasional)',
            'Fotokopi Kartu Keluarga (KK)',
            'Fotokopi Akta Kelahiran',
            'Pas foto berwarna 3×4 (4 lembar)',
            'Sertifikat prestasi akademik/non-akademik (jika ada)',
        ];

        foreach ($persyaratans as $i => $dok) {
            PpdbPersyaratan::create([
                'tahun_ajaran' => '2025/2026',
                'dokumen'      => $dok,
                'urutan'       => $i + 1,
                'is_active'    => true,
            ]);
        }

        // ── BIAYA ─────────────────────────────────────────
        $biaya = [
            ['nama_biaya' => 'Biaya Pendaftaran', 'nominal' => 150000],
            ['nama_biaya' => 'Uang Pangkal',      'nominal' => 5000000],
            ['nama_biaya' => 'SPP per Bulan',     'nominal' => 750000],
            ['nama_biaya' => 'Seragam & Atribut', 'nominal' => 800000],
            ['nama_biaya' => 'Kegiatan MPLS',     'nominal' => 300000],
        ];

        foreach ($biaya as $i => $b) {
            PpdbBiaya::create(array_merge($b, [
                'tahun_ajaran' => '2025/2026',
                'urutan'       => $i + 1,
                'is_active'    => true,
            ]));
        }
    }
}