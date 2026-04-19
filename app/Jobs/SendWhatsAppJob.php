<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public string $appointmentId,
        public string $type, // 'booking_confirmation' | 'reminder_h1' | 'reminder_h2jam'
    ) {}

    public function handle(WhatsAppService $whatsAppService): void
    {
        $appointment = Appointment::with('doctor')->find($this->appointmentId);

        if (! $appointment) {
            Log::warning('[SendWhatsAppJob] Appointment not found: ' . $this->appointmentId);
            return;
        }

        $sent = match ($this->type) {
            'booking_confirmation' => $whatsAppService->sendBookingConfirmation($appointment),
            'reminder_h1'          => $whatsAppService->sendReminderH1($appointment),
            'reminder_h2jam'       => $whatsAppService->sendReminderH2Jam($appointment),
            default                => false,
        };

        if ($sent) {
            $field = match ($this->type) {
                'booking_confirmation' => 'wa_sent_at',
                'reminder_h1'          => 'reminder_h1_sent_at',
                'reminder_h2jam'       => 'reminder_h2jam_sent_at',
                default                => null,
            };

            if ($field) {
                $appointment->update([$field => now()]);
            }
        }
    }
}
