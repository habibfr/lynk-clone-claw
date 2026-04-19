@extends('layouts.admin')
@section('title', 'Tambah Dokter')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 mb-5">Data Dokter</h2>

        <form method="POST" action="{{ route('admin.doctors.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Dokter *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="dr. Nama Dokter, Sp.X"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Spesialisasi</label>
                <input type="text" name="specialization" value="{{ old('specialization') }}"
                       placeholder="Dokter Umum / Spesialis Anak / dll"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       placeholder="08xx-xxxx-xxxx"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center gap-3">
                <div class="relative inline-flex items-center cursor-pointer" x-data="{ checked: true }">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                           id="is_active"
                           class="sr-only peer">
                    <label for="is_active" class="flex items-center gap-3 cursor-pointer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500 relative"></div>
                        <span class="text-sm font-medium text-slate-700">Dokter Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Simpan Dokter
                </button>
                <a href="{{ route('admin.doctors.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
