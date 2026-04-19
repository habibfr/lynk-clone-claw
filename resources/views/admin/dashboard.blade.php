@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Date Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" class="flex gap-2 items-center">
            <input type="date" name="date" value="{{ $date }}"
                   onchange="this.form.submit()"
                   class="border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
            <a href="{{ route('admin.dashboard') }}"
               class="text-sm text-slate-500 hover:text-slate-700 px-3 py-2">Reset</a>
        </form>
        <p class="text-sm text-slate-500">
            Menampilkan data:
            <span class="font-semibold text-slate-800">{{ $carbonDate->locale('id')->translatedFormat('l, d F Y') }}</span>
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $stats['total_today'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Appointment Hari Ini</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $stats['scheduled'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Terjadwal</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $stats['done'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Selesai</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $stats['total_doctors'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Dokter Aktif</p>
        </div>
    </div>

    {{-- Today's Appointment Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Antrian Hari Ini</h2>
            <a href="{{ route('admin.appointments.index', ['date' => $date]) }}"
               class="text-sky-500 text-sm hover:text-sky-600 font-medium">Lihat Semua →</a>
        </div>

        @if($todayAppointments->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium">Belum ada appointment</p>
            <p class="text-sm mt-1">pada tanggal ini</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Antrian</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dokter</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($todayAppointments as $apt)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-sky-100 text-sky-700 font-black text-sm">
                                {{ $apt->queue_number }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-800 text-sm">{{ $apt->patient_name }}</p>
                            <p class="text-slate-400 text-xs">{{ $apt->patient_phone }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-700 text-sm">{{ $apt->doctor->name }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-800 font-semibold text-sm">{{ $apt->formatted_time }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $colorMap = [
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'done'      => 'bg-slate-100 text-slate-600',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colorMap[$apt->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $apt->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2" x-data>
                                <form method="POST" action="{{ route('admin.appointments.update-status', $apt->id) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 text-slate-600 focus:outline-none focus:border-sky-400 bg-white">
                                        @foreach(\App\Models\Appointment::STATUS_LABELS as $val => $label)
                                        <option value="{{ $val }}" @selected($apt->status === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
