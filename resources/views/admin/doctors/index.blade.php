@extends('layouts.admin')
@section('title', 'Kelola Dokter')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div></div>
        <a href="{{ route('admin.doctors.create') }}"
           class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Dokter
        </a>
    </div>

    {{-- Grid Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($doctors as $doctor)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 h-2"></div>
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-black text-xl">{{ mb_substr($doctor->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $doctor->name }}</p>
                        <p class="text-sky-600 text-sm">{{ $doctor->specialization ?: 'Dokter Umum' }}</p>
                        @if($doctor->phone)
                        <p class="text-slate-400 text-xs mt-1">{{ $doctor->phone }}</p>
                        @endif
                    </div>
                    <div>
                        @if($doctor->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between text-center">
                    <div>
                        <p class="text-xl font-black text-slate-800">{{ $doctor->schedules_count }}</p>
                        <p class="text-xs text-slate-500">Jadwal</p>
                    </div>
                    <div>
                        <p class="text-xl font-black text-slate-800">{{ $doctor->appointments_count }}</p>
                        <p class="text-xs text-slate-500">Total Appointment</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.doctors.edit', $doctor) }}"
                           class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-sky-100 flex items-center justify-center text-slate-600 hover:text-sky-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}"
                              onsubmit="return confirm('Hapus dokter ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-red-100 flex items-center justify-center text-slate-600 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 text-slate-400 bg-white rounded-2xl border border-slate-100">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p class="font-medium">Belum ada dokter</p>
            <a href="{{ route('admin.doctors.create') }}" class="mt-3 inline-block text-sky-500 text-sm hover:text-sky-600">+ Tambah dokter pertama</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($doctors->hasPages())
    <div>{{ $doctors->links() }}</div>
    @endif

</div>
@endsection
