<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctors = Doctor::where('is_active', true)->with('schedules')->orderBy('name')->get();
        return view('admin.schedules.index', compact('doctors'));
    }

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'              => ['required', 'uuid', 'exists:doctors,id'],
            'day_of_week'            => ['required', 'integer', 'between:0,6'],
            'start_time'             => ['required', 'date_format:H:i'],
            'end_time'               => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration_minutes'  => ['required', 'integer', 'in:15,20,30,45,60'],
            'max_patients'           => ['required', 'integer', 'min:1', 'max:100'],
            'is_active'              => ['boolean'],
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'doctor_id'              => ['required', 'uuid', 'exists:doctors,id'],
            'day_of_week'            => ['required', 'integer', 'between:0,6'],
            'start_time'             => ['required', 'date_format:H:i'],
            'end_time'               => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration_minutes'  => ['required', 'integer', 'in:15,20,30,45,60'],
            'max_patients'           => ['required', 'integer', 'min:1', 'max:100'],
            'is_active'              => ['boolean'],
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
