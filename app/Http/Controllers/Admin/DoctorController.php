<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::withCount(['appointments', 'schedules'])->orderBy('name')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'is_active'      => ['boolean'],
        ]);

        Doctor::create($validated);

        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('schedules');
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'is_active'      => ['boolean'],
        ]);

        $doctor->update($validated);

        return redirect()->route('admin.doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete(); // soft delete
        return back()->with('success', 'Dokter berhasil dihapus.');
    }
}
