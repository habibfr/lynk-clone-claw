<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Jobs\SendWhatsAppJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        private SlotGeneratorService $slotGenerator,
        private QueueNumberService $queueNumberService,
    ) {}

    /**
     * Get available slots for a doctor on a date.
     *
     * @return array{slots: array<string>, doctor: Doctor|null, schedule_exists: bool}
     */
    public function getAvailableSlots(string $doctorId, string $date): array
    {
        $carbon = Carbon::parse($date);
        $doctor = Doctor::where('id', $doctorId)->where('is_active', true)->first();

        if (! $doctor) {
            return ['slots' => [], 'doctor' => null, 'schedule_exists' => false];
        }

        $slots = $this->slotGenerator->generate($doctorId, $carbon);

        return [
            'slots'           => $slots,
            'doctor'          => $doctor,
            'schedule_exists' => count($slots) > 0 || $this->hasSchedule($doctorId, $carbon),
        ];
    }

    /**
     * Create a booking (with double-booking protection via DB transaction + unique constraint).
     *
     * @throws \Exception on double booking or invalid data
     */
    public function createBooking(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $doctorId = $data['doctor_id'];
            $date     = $data['appointment_date'];
            $time     = $data['appointment_time'];

            // Double-check slot is still available
            $exists = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $date)
                ->where('appointment_time', $time)
                ->whereNotIn('status', [Appointment::STATUS_CANCELLED])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                throw new \Exception('Slot ini sudah dibooking. Silakan pilih waktu lain.');
            }

            $queueNumber = $this->queueNumberService->generate($doctorId, $date);

            $appointment = Appointment::create([
                'doctor_id'        => $doctorId,
                'patient_name'     => $data['patient_name'],
                'patient_phone'    => $data['patient_phone'],
                'appointment_date' => $date,
                'appointment_time' => $time . ':00',
                'queue_number'     => $queueNumber,
                'status'           => Appointment::STATUS_SCHEDULED,
                'notes'            => $data['notes'] ?? null,
            ]);

            // Dispatch WhatsApp notification async
            SendWhatsAppJob::dispatch($appointment->id, 'booking_confirmation');

            return $appointment;
        });
    }

    private function hasSchedule(string $doctorId, Carbon $date): bool
    {
        return \App\Models\Schedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $date->dayOfWeek)
            ->where('is_active', true)
            ->exists();
    }
}
