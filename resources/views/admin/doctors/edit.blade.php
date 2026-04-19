@extends('layouts.admin')
@section('title', 'Edit Dokter')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 mb-5">Edit Data Dokter</h2>

        <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Dokter *</label>
                <input type="text" name="name" value="{{ old('name', $doctor->name) }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Spesialisasi</label>
                <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $doctor->is_active))
                       id="is_active"
                       class="w-4 h-4 text-sky-500 border-slate-300 rounded focus:ring-sky-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Dokter Aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Update Dokter
                </button>
                <a href="{{ route('admin.doctors.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Schedules for this doctor --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm mt-5 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Jadwal Praktik</h3>
            <a href="{{ route('admin.schedules.create', ['doctor_id' => $doctor->id]) }}"
               class="text-sky-500 text-sm hover:text-sky-600 font-medium">+ Tambah Jadwal</a>
        </div>
        @if($doctor->schedules->isEmpty())
        <div class="p-8 text-center text-slate-400 text-sm">Belum ada jadwal</div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($doctor->schedules->sortBy('day_of_week') as $schedule)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $schedule->day_name }}</p>
                    <p class="text-slate-500 text-xs">{{ $schedule->start_time }} – {{ $schedule->end_time }} · {{ $schedule->slot_duration_minutes }} menit/slot</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($schedule->is_active)
                    <span class="text-xs text-green-600 font-medium">Aktif</span>
                    @else
                    <span class="text-xs text-slate-400 font-medium">Nonaktif</span>
                    @endif
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 hover:text-sky-600 hover:bg-sky-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
