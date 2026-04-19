@extends('layouts.admin')
@section('title', 'Edit Jadwal')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 mb-5">Edit Jadwal Praktik</h2>

        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dokter *</label>
                <select name="doctor_id"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" @selected(old('doctor_id', $schedule->doctor_id) === $doc->id)>{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hari Praktik *</label>
                <select name="day_of_week"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @foreach(\App\Models\Schedule::DAY_NAMES as $val => $name)
                    <option value="{{ $val }}" @selected(old('day_of_week', $schedule->day_of_week) == $val)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Mulai *</label>
                    <input type="time" name="start_time"
                           value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Selesai *</label>
                    <input type="time" name="end_time"
                           value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi per Pasien</label>
                    <select name="slot_duration_minutes"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @foreach([15 => '15 menit', 20 => '20 menit', 30 => '30 menit', 45 => '45 menit', 60 => '1 jam'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('slot_duration_minutes', $schedule->slot_duration_minutes) == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Maks. Pasien</label>
                    <input type="number" name="max_patients" value="{{ old('max_patients', $schedule->max_patients) }}"
                           min="1" max="100"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $schedule->is_active))
                       id="is_active"
                       class="w-4 h-4 text-sky-500 border-slate-300 rounded focus:ring-sky-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Jadwal Aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Update Jadwal
                </button>
                <a href="{{ route('admin.schedules.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
