<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
    ) {}

    /**
     * Show the booking page.
     */
    public function index()
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();
        return view('patient.booking', compact('doctors'));
    }

    /**
     * GET /booking/slots?doctor_id=&date=
     * Returns JSON list of available slots.
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => ['required', 'uuid'],
            'date'      => ['required', 'date', 'after_or_equal:today'],
        ]);

        $result = $this->bookingService->getAvailableSlots(
            $request->doctor_id,
            $request->date
        );

        return response()->json([
            'slots'           => $result['slots'],
            'schedule_exists' => $result['schedule_exists'],
        ]);
    }

    /**
     * POST /booking — Create a new appointment.
     */
    public function store(BookingRequest $request)
    {
        try {
            $appointment = $this->bookingService->createBooking($request->validated());

            return redirect()->route('booking.success', $appointment->id)
                ->with('success', 'Booking berhasil!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Booking success page.
     */
    public function success(string $id)
    {
        $appointment = Appointment::with('doctor')->findOrFail($id);
        return view('patient.booking-success', compact('appointment'));
    }
}
