<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Appointment;
use App\Jobs\SendWhatsAppJob;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ─── WA REMINDERS SCHEDULER ──────────────────────────────────────────────────

// 1. Reminder H-1 (Setiap jam 08:00 pagi untuk jadwal besok)
Schedule::call(function () {
    $tomorrow = Carbon::tomorrow()->toDateString();
    
    $appointments = Appointment::whereDate('appointment_date', $tomorrow)
        ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_CONFIRMED])
        ->whereNull('reminder_h1_sent_at')
        ->get();

    foreach ($appointments as $apt) {
        SendWhatsAppJob::dispatch($apt->id, 'reminder_h1');
    }
})->dailyAt('08:00')->name('send_reminder_h1')->withoutOverlapping();


// 2. Reminder H-2 Jam (Setiap 5 menit cek jadwal yang akan datang dalam 2 jam)
Schedule::call(function () {
    $now = Carbon::now();
    $targetTime = $now->copy()->addHours(2);
    
    // Cari appointment HARI INI yang jamnya sekitar 2 jam lagi
    $appointments = Appointment::whereDate('appointment_date', $now->toDateString())
        ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_CONFIRMED])
        ->whereNull('reminder_h2jam_sent_at')
        ->whereTime('appointment_time', '<=', $targetTime->format('H:i:s'))
        ->whereTime('appointment_time', '>', $now->format('H:i:s'))
        ->get();

    foreach ($appointments as $apt) {
        SendWhatsAppJob::dispatch($apt->id, 'reminder_h2jam');
    }
})->everyFiveMinutes()->name('send_reminder_h2jam')->withoutOverlapping();
