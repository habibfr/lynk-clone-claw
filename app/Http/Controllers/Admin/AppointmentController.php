<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('doctor')->orderBy('appointment_date', 'desc')->orderBy('appointment_time');

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();
        $doctors = Doctor::orderBy('name')->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('doctor');
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:scheduled,confirmed,done,cancelled'],
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Status appointment berhasil diperbarui.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete(); // soft delete
        return back()->with('success', 'Appointment berhasil dihapus.');
    }
}
