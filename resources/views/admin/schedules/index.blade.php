@extends('layouts.admin')
@section('title', 'Jadwal Dokter')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div></div>
        <a href="{{ route('admin.schedules.create') }}"
           class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </a>
    </div>

    @foreach($doctors as $doctor)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold">{{ mb_substr($doctor->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="font-bold text-slate-800">{{ $doctor->name }}</p>
                <p class="text-slate-500 text-xs">{{ $doctor->specialization ?: 'Dokter Umum' }}</p>
            </div>
        </div>

        @if($doctor->schedules->isEmpty())
        <div class="p-6 text-center text-slate-400 text-sm">
            Belum ada jadwal.
            <a href="{{ route('admin.schedules.create', ['doctor_id' => $doctor->id]) }}" class="text-sky-500 hover:text-sky-600">+ Tambah jadwal</a>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($doctor->schedules->sortBy('day_of_week') as $schedule)
            <div class="px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-20 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $schedule->is_active ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $schedule->day_name }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </p>
                        <p class="text-slate-500 text-xs">
                            {{ $schedule->slot_duration_minutes }} menit/pasien ·
                            Maks {{ $schedule->max_patients }} pasien
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.schedules.edit', $schedule) }}"
                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-sky-100 flex items-center justify-center text-slate-500 hover:text-sky-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                          onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-100 flex items-center justify-center text-slate-500 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach

    @if($doctors->isEmpty())
    <div class="text-center py-16 text-slate-400 bg-white rounded-2xl border border-slate-100">
        <p class="font-medium">Belum ada dokter.</p>
        <a href="{{ route('admin.doctors.create') }}" class="mt-3 inline-block text-sky-500 text-sm">Tambah dokter dulu →</a>
    </div>
    @endif

</div>
@endsection
