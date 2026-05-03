<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\SawResult;
use App\Jobs\PublishResultNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublishResultController extends Controller
{
    /**
     * Halaman preview sebelum publish — panitia bisa review ringkasan dulu.
     */
    public function preview()
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif');
        }

        // Cek prasyarat: acceptance harus sudah ditentukan
        $acceptanceDone = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->where('final_status', '!=', 'pending')
            ->exists();

        // Ringkasan untuk preview
        $summary = $this->buildPublishSummary($activeYear->id);

        return view('committee.publish-result.preview', compact(
            'activeYear',
            'acceptanceDone',
            'summary'
        ));
    }

    /**
     * Ubah status menjadi "reviewing" — panitia sedang memeriksa.
     */
    public function setReviewing(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif');
        }

        if ($activeYear->result_status === 'published') {
            return redirect()->back()->with('error', 'Hasil sudah dipublikasikan dan tidak bisa diubah.');
        }

        $activeYear->update(['result_status' => 'reviewing']);

        Log::info('Result status set to reviewing', [
            'academic_year_id' => $activeYear->id,
            'by_user'          => auth()->id(),
        ]);

        return redirect()->route('committee.publish-result.preview')
            ->with('success', 'Status diubah ke "Sedang Direview". Silakan periksa data sebelum mempublikasikan.');
    }

    /**
     * Publikasikan hasil — siswa baru bisa melihat setelah ini.
     */
    public function publish(Request $request)
    {
        $request->validate([
            'publish_notes' => 'nullable|string|max:1000',
            'confirm'       => 'required|accepted',
        ], [
            'confirm.required' => 'Anda harus mencentang konfirmasi sebelum mempublikasikan.',
            'confirm.accepted' => 'Anda harus mencentang konfirmasi sebelum mempublikasikan.',
        ]);

        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif');
        }

        // Prasyarat 1: acceptance sudah ditentukan
        $allPending = Student::where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid')
            ->where('final_status', 'pending')
            ->count();

        if ($allPending > 0) {
            return redirect()->back()
                ->with('error', "Masih ada {$allPending} siswa dengan status Menunggu. Tentukan status penerimaan terlebih dahulu.");
        }

        // Prasyarat 2: SAW sudah dihitung untuk tahfiz & language
        $tahfizCalculated   = SawResult::where('academic_year_id', $activeYear->id)->where('specialization', 'tahfiz')->exists();
        $languageCalculated = SawResult::where('academic_year_id', $activeYear->id)->where('specialization', 'language')->exists();

        if (!$tahfizCalculated || !$languageCalculated) {
            return redirect()->back()
                ->with('error', 'Perhitungan SAW belum lengkap. Pastikan SAW untuk Tahfiz dan Bahasa sudah dihitung.');
        }

        // Sudah dipublikasikan?
        if ($activeYear->result_status === 'published') {
            return redirect()->back()->with('error', 'Hasil sudah dipublikasikan sebelumnya.');
        }

        $activeYear->update([
            'result_status' => 'published',
            'published_at'  => now(),
            'published_by'  => auth()->id(),
            'publish_notes' => $request->input('publish_notes'),
        ]);

        Log::info('Result published', [
            'academic_year_id' => $activeYear->id,
            'by_user'          => auth()->id(),
            'published_at'     => now(),
        ]);

        // Dispatch job notifikasi ke semua siswa (async, tidak blokir response)
        PublishResultNotificationJob::dispatch($activeYear->id)->onQueue('notifications');

        return redirect()->route('committee.saw-results.index')
            ->with('success', '✅ Hasil berhasil dipublikasikan! Siswa kini dapat melihat hasil seleksi mereka. Notifikasi sedang dikirim.');
    }

    /**
     * Tarik kembali publikasi (unpublish) — hanya jika benar-benar perlu koreksi.
     */
    public function unpublish(Request $request)
    {
        $request->validate([
            'unpublish_reason' => 'required|string|min:10|max:500',
        ], [
            'unpublish_reason.required' => 'Alasan penarikan wajib diisi.',
            'unpublish_reason.min'      => 'Alasan minimal 10 karakter.',
        ]);

        $activeYear = AcademicYear::getActiveYear();

        if (!$activeYear || $activeYear->result_status !== 'published') {
            return redirect()->back()->with('error', 'Tidak ada hasil yang dapat ditarik.');
        }

        $activeYear->update([
            'result_status' => 'reviewing',
            'published_at'  => null,
            'publish_notes' => '[DITARIK] ' . $request->input('unpublish_reason'),
        ]);

        Log::warning('Result unpublished', [
            'academic_year_id' => $activeYear->id,
            'by_user'          => auth()->id(),
            'reason'           => $request->input('unpublish_reason'),
        ]);

        return redirect()->route('committee.publish-result.preview')
            ->with('warning', 'Publikasi hasil telah ditarik. Siswa tidak lagi dapat melihat hasil seleksi.');
    }

    // -----------------------------------------------------------------------
    // PRIVATE
    // -----------------------------------------------------------------------

    private function buildPublishSummary(int $academicYearId): array
    {
        $base = Student::where('academic_year_id', $academicYearId)
            ->where('validation_status', 'valid');

        return [
            'total'          => (clone $base)->count(),
            'accepted'       => (clone $base)->where('final_status', 'accepted')->count(),
            'rejected'       => (clone $base)->where('final_status', 'rejected')->count(),
            'pending'        => (clone $base)->where('final_status', 'pending')->count(),
            'dual_pass'      => (clone $base)->where('dual_pass', true)->count(),
            'cross_accepted' => (clone $base)->where('cross_accepted', true)->count(),
            'tahfiz'         => (clone $base)->where('specialization', 'tahfiz')->where('final_status', 'accepted')->count(),
            'language'       => (clone $base)->where('specialization', 'language')->where('final_status', 'accepted')->count(),
            'regular'        => (clone $base)->where('specialization', 'regular')->where('final_status', 'accepted')->count(),
        ];
    }
}