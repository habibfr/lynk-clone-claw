<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        $carbonDate = Carbon::parse($date);

        $todayAppointments = Appointment::with('doctor')
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get();

        $stats = [
            'total_today'     => $todayAppointments->count(),
            'scheduled'       => $todayAppointments->where('status', 'scheduled')->count(),
            'confirmed'       => $todayAppointments->where('status', 'confirmed')->count(),
            'done'            => $todayAppointments->where('status', 'done')->count(),
            'total_doctors'   => Doctor::where('is_active', true)->count(),
            'total_this_week' => Appointment::whereBetween('appointment_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count(),
        ];

        return view('admin.dashboard', compact('todayAppointments', 'stats', 'date', 'carbonDate'));
    }
}
