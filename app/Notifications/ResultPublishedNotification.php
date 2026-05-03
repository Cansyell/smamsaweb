<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Student $student) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status     = $this->student->final_status === 'accepted' ? 'DITERIMA' : 'TIDAK DITERIMA';
        $isDualPass = $this->student->dual_pass;
        $recommended = $this->student->recommended_specialization;

        $mail = (new MailMessage)
            ->subject('Hasil Seleksi PPDB - ' . $this->student->full_name)
            ->greeting('Assalamualaikum, ' . $this->student->full_name)
            ->line('Hasil seleksi Penerimaan Peserta Didik Baru telah dipublikasikan.')
            ->line('Status Anda: **' . $status . '**');

        if ($this->student->final_status === 'accepted') {
            $mail->line('Selamat! Anda diterima di spesialisasi: **' . ucfirst($this->student->accepted_specialization) . '**');

            if ($isDualPass && $recommended && $recommended !== $this->student->specialization) {
                $mail->line(
                    '📌 Anda lulus di dua spesialisasi. Skor SAW Anda lebih tinggi di **' .
                    ucfirst($recommended) . '**. Pertimbangkan untuk pindah ke spesialisasi tersebut.'
                );
            }
        }

        return $mail
            ->action('Lihat Detail Hasil', url('/student/result'))
            ->line('Hubungi panitia jika ada pertanyaan.')
            ->salutation('Panitia PPDB');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'        => 'Hasil Seleksi Dipublikasikan',
            'message'      => 'Status Anda: ' . strtoupper($this->student->final_status ?? 'pending'),
            'final_status' => $this->student->final_status,
            'dual_pass'    => $this->student->dual_pass,
            'recommended'  => $this->student->recommended_specialization,
            'url'          => '/student/result',
        ];
    }
}