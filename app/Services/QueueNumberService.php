<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class QueueNumberService
{
    /**
     * Generate the next queue number for a doctor on a given date.
     * Format: A1, A2, A3...
     */
    public function generate(string $doctorId, string $date): string
    {
        $count = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', [Appointment::STATUS_CANCELLED])
            ->count();

        return 'A' . ($count + 1);
    }
}
