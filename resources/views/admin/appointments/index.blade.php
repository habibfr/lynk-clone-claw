@extends('layouts.admin')
@section('title', 'Daftar Appointment')

@section('content')
<div class="space-y-5">

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Dokter</label>
                <select name="doctor_id" class="border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">Semua Dokter</option>
                    @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" @selected(request('doctor_id') === $doc->id)>{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select name="status" class="border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Appointment::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-xl text-sm font-semibold transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.appointments.index') }}"
               class="text-slate-500 hover:text-slate-700 px-3 py-2 text-sm">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">
                Appointment
                <span class="text-slate-400 font-normal text-sm ml-2">({{ $appointments->total() }} data)</span>
            </h2>
        </div>

        @if($appointments->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-medium">Tidak ada appointment ditemukan</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Antrian</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dokter</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal & Jam</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">WA Terkirim</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($appointments as $apt)
                    @php
                        $colorMap = [
                            'scheduled' => 'bg-blue-100 text-blue-700',
                            'confirmed' => 'bg-green-100 text-green-700',
                            'done'      => 'bg-slate-100 text-slate-600',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
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
                            <p class="text-slate-800 text-sm font-medium">{{ $apt->appointment_date->format('d M Y') }}</p>
                            <p class="text-slate-500 text-xs">{{ $apt->formatted_time }} WIB</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($apt->wa_sent_at)
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Terkirim
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">Belum</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colorMap[$apt->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $apt->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.appointments.update-status', $apt->id) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 text-slate-600 bg-white focus:outline-none focus:border-sky-400">
                                        @foreach(\App\Models\Appointment::STATUS_LABELS as $val => $label)
                                        <option value="{{ $val }}" @selected($apt->status === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <form method="POST" action="{{ route('admin.appointments.destroy', $apt->id) }}"
                                      onsubmit="return confirm('Hapus appointment ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($appointments->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $appointments->links() }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection
