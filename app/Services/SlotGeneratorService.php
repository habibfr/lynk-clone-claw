<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;

class SlotGeneratorService
{
    /**
     * Generate available time slots for a doctor on a given date.
     *
     * @return array<string> e.g. ["08:00", "08:15", "08:30", ...]
     */
    public function generate(string $doctorId, Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;

        $schedule = Schedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $schedule) {
            return [];
        }

        // Generate all slots
        $allSlots = $this->generateSlots($schedule);

        // Get booked slots for this date
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', [Appointment::STATUS_CANCELLED])
            ->pluck('appointment_time')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        // Filter out booked slots
        return array_values(array_filter(
            $allSlots,
            fn ($slot) => ! in_array($slot, $bookedSlots)
        ));
    }

    /**
     * Generate all time slots from schedule (without filtering booked).
     *
     * @return array<string>
     */
    private function generateSlots(Schedule $schedule): array
    {
        $slots = [];
        $current = Carbon::parse($schedule->start_time);
        $end     = Carbon::parse($schedule->end_time);
        $duration = (int) $schedule->slot_duration_minutes;

        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($duration);
        }

        return $slots;
    }
}
