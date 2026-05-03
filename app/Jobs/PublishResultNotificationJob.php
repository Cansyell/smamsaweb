<?php

namespace App\Jobs;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Notifications\ResultPublishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishResultNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 300;

    public function __construct(public readonly int $academicYearId) {}

    public function handle(): void
    {
        $students = Student::with('user')
            ->where('academic_year_id', $this->academicYearId)
            ->where('validation_status', 'valid')
            ->whereNotNull('final_status')
            ->get();

        foreach ($students as $student) {
            try {
                if ($student->user) {
                    $student->user->notify(
                        new ResultPublishedNotification($student)
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal kirim notifikasi ke siswa', [
                    'student_id' => $student->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        Log::info('Result notification sent', [
            'academic_year_id' => $this->academicYearId,
            'total_notified'   => $students->count(),
        ]);
    }
}