<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    private bool $isMock;
    private string $token;

    public function __construct()
    {
        $this->isMock = env('WABLAST_MOCK', true);
        $this->token  = env('WABLAST_TOKEN', '');
    }

    /**
     * Send booking confirmation message.
     */
    public function sendBookingConfirmation(Appointment $appointment): bool
    {
        $doctor = $appointment->doctor;
        $date   = Carbon::parse($appointment->appointment_date)->locale('id')->translatedFormat('l, d F Y');
        $time   = Carbon::parse($appointment->appointment_time)->format('H:i');

        $message = "Halo *{$appointment->patient_name}*,\n\n"
            . "✅ Booking berhasil!\n\n"
            . "🏥 *" . config('app.clinic_name', 'Klinik Sehat') . "*\n"
            . "👨‍⚕️ {$doctor->name}" . ($doctor->specialization ? " ({$doctor->specialization})" : '') . "\n"
            . "📅 {$date}\n"
            . "⏰ {$time} WIB\n"
            . "🎟️ Nomor Antrian: *{$appointment->queue_number}*\n\n"
            . "Mohon datang 10 menit sebelum jadwal.\n"
            . "📍 " . config('app.clinic_address', '') . "\n\n"
            . "_Pesan ini dikirim otomatis oleh sistem._";

        return $this->send($appointment->patient_phone, $message);
    }

    /**
     * Send H-1 reminder message.
     */
    public function sendReminderH1(Appointment $appointment): bool
    {
        $doctor = $appointment->doctor;
        $time   = Carbon::parse($appointment->appointment_time)->format('H:i');

        $message = "Halo *{$appointment->patient_name}*,\n\n"
            . "⏰ Pengingat: Besok Anda memiliki jadwal konsultasi\n\n"
            . "👨‍⚕️ {$doctor->name}\n"
            . "⏰ {$time} WIB\n"
            . "🎟️ Antrian: *{$appointment->queue_number}*\n\n"
            . "Balas *1* untuk konfirmasi kehadiran.\n"
            . "Balas *0* untuk batalkan.\n\n"
            . "_Klinik Sehat_";

        return $this->send($appointment->patient_phone, $message);
    }

    /**
     * Send 2-hour before reminder.
     */
    public function sendReminderH2Jam(Appointment $appointment): bool
    {
        $doctor = $appointment->doctor;
        $time   = Carbon::parse($appointment->appointment_time)->format('H:i');

        $message = "Halo *{$appointment->patient_name}*,\n\n"
            . "⏰ Jadwal Anda *2 jam lagi* dengan {$doctor->name} pukul {$time} WIB\n"
            . "🎟️ Antrian: *{$appointment->queue_number}*\n\n"
            . "Silakan bersiap dan datang tepat waktu.\n\n"
            . "_Klinik Sehat_";

        return $this->send($appointment->patient_phone, $message);
    }

    /**
     * Core send method — mock or real Fonnte API.
     */
    private function send(string $phone, string $message): bool
    {
        // Normalize phone number
        $phone = $this->normalizePhone($phone);

        if ($this->isMock) {
            Log::channel('single')->info('[WhatsApp MOCK] Sending to: ' . $phone, [
                'message' => $message,
            ]);
            return true;
        }

        // Real: Fonnte API
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
                'delay'   => 2,
            ]);

            if ($response->successful() && $response->json('status')) {
                Log::info('[WhatsApp] Sent to: ' . $phone);
                return true;
            }

            Log::error('[WhatsApp] Failed to send', [
                'phone'    => $phone,
                'response' => $response->json(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('[WhatsApp] Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function normalizePhone(string $phone): string
    {
        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Convert 08xx to 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Remove leading +
        $phone = ltrim($phone, '+');

        return $phone;
    }
}
