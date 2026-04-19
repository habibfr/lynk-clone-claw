@extends('layouts.patient')
@section('title', 'Booking Dokter')

@section('content')
<div x-data="bookingForm()" class="space-y-5">

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center gap-2 mb-2">
        <div class="flex items-center gap-2">
            <div :class="step >= 1 ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400'"
                 class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300">1</div>
            <span :class="step >= 1 ? 'text-sky-600' : 'text-slate-400'" class="text-xs font-medium">Pilih Dokter</span>
        </div>
        <div :class="step >= 2 ? 'bg-sky-500' : 'bg-slate-200'" class="h-0.5 w-8 transition-all duration-300"></div>
        <div class="flex items-center gap-2">
            <div :class="step >= 2 ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400'"
                 class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300">2</div>
            <span :class="step >= 2 ? 'text-sky-600' : 'text-slate-400'" class="text-xs font-medium">Pilih Jadwal</span>
        </div>
        <div :class="step >= 3 ? 'bg-sky-500' : 'bg-slate-200'" class="h-0.5 w-8 transition-all duration-300"></div>
        <div class="flex items-center gap-2">
            <div :class="step >= 3 ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400'"
                 class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300">3</div>
            <span :class="step >= 3 ? 'text-sky-600' : 'text-slate-400'" class="text-xs font-medium">Data Pasien</span>
        </div>
    </div>

    {{-- STEP 1: Pilih Dokter & Tanggal --}}
    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Pilih Dokter</h2>
                <p class="text-slate-500 text-sm mt-1">Pilih dokter dan tanggal kunjungan</p>
            </div>

            {{-- Dokter cards --}}
            <div class="space-y-3">
                @foreach ($doctors as $doctor)
                <label class="block cursor-pointer">
                    <input type="radio" name="doctor_radio" value="{{ $doctor->id }}"
                           x-model="doctorId"
                           @change="doctorName = '{{ $doctor->name }}'; selectedTime = ''; slots = []"
                           class="sr-only peer">
                    <div class="border-2 rounded-xl p-4 transition-all duration-200 peer-checked:border-sky-500 peer-checked:bg-sky-50 border-slate-200 hover:border-sky-300">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-lg">{{ mb_substr($doctor->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800">{{ $doctor->name }}</p>
                                @if($doctor->specialization)
                                <p class="text-sky-600 text-sm">{{ $doctor->specialization }}</p>
                                @endif
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-sky-500 flex items-center justify-center flex-shrink-0"
                                 :class="doctorId === '{{ $doctor->id }}' ? 'border-sky-500 bg-sky-500' : 'border-slate-300'">
                                <svg x-show="doctorId === '{{ $doctor->id }}'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach

                @if($doctors->isEmpty())
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p class="text-sm">Belum ada dokter aktif</p>
                </div>
                @endif
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kunjungan</label>
                <input type="date"
                       x-model="date"
                       :min="today"
                       :max="maxDate"
                       class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-sky-500 transition-colors text-base"
                       placeholder="Pilih tanggal">
            </div>

            <button @click="goStep2()"
                    :disabled="!doctorId || !date"
                    class="w-full bg-sky-500 hover:bg-sky-600 disabled:bg-slate-200 disabled:text-slate-400 text-white font-semibold py-4 rounded-xl transition-all duration-200 text-base active:scale-95">
                Lihat Jadwal Tersedia →
            </button>
        </div>
    </div>

    {{-- STEP 2: Pilih Jam --}}
    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
            <div class="flex items-center gap-3">
                <button @click="step = 1" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Pilih Jam</h2>
                    <p class="text-slate-500 text-sm" x-text="doctorName + ' · ' + formatDate(date)"></p>
                </div>
            </div>

            {{-- Loading --}}
            <div x-show="loadingSlots" class="text-center py-8">
                <div class="inline-block w-8 h-8 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-slate-500 text-sm mt-3">Mengambil jadwal...</p>
            </div>

            {{-- No schedule --}}
            <div x-show="!loadingSlots && !scheduleExists" class="text-center py-8 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">Dokter tidak praktik pada hari ini</p>
                <p class="text-xs mt-1">Coba pilih tanggal lain</p>
                <button @click="step = 1" class="mt-4 text-sky-500 text-sm font-medium hover:text-sky-600">← Kembali pilih tanggal</button>
            </div>

            {{-- No slots --}}
            <div x-show="!loadingSlots && scheduleExists && slots.length === 0" class="text-center py-8 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium">Semua slot sudah penuh</p>
                <p class="text-xs mt-1">Coba pilih tanggal lain</p>
                <button @click="step = 1" class="mt-4 text-sky-500 text-sm font-medium hover:text-sky-600">← Kembali</button>
            </div>

            {{-- Slot Grid --}}
            <div x-show="!loadingSlots && slots.length > 0" class="grid grid-cols-3 gap-2">
                <template x-for="slot in slots" :key="slot">
                    <button type="button"
                            @click="selectedTime = slot"
                            :class="selectedTime === slot
                                ? 'bg-sky-500 text-white border-sky-500 shadow-md scale-105'
                                : 'bg-white text-slate-700 border-slate-200 hover:border-sky-300 hover:bg-sky-50'"
                            class="border-2 rounded-xl py-3 text-sm font-semibold transition-all duration-150 active:scale-95"
                            x-text="slot">
                    </button>
                </template>
            </div>

            <button @click="step = 3"
                    x-show="slots.length > 0"
                    :disabled="!selectedTime"
                    class="w-full bg-sky-500 hover:bg-sky-600 disabled:bg-slate-200 disabled:text-slate-400 text-white font-semibold py-4 rounded-xl transition-all duration-200 text-base active:scale-95">
                Lanjut ke Data Pasien →
            </button>
        </div>
    </div>

    {{-- STEP 3: Data Pasien --}}
    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
            <div class="flex items-center gap-3">
                <button @click="step = 2" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Data Pasien</h2>
                    <p class="text-slate-500 text-sm">Isi nama dan nomor WhatsApp</p>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-sky-50 rounded-xl p-4 border border-sky-100">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-slate-500 text-xs">Dokter</p>
                        <p class="font-semibold text-slate-800" x-text="doctorName"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs">Tanggal</p>
                        <p class="font-semibold text-slate-800" x-text="formatDate(date)"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs">Jam</p>
                        <p class="font-semibold text-sky-600 text-base" x-text="selectedTime + ' WIB'"></p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('booking.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="doctor_id" :value="doctorId">
                <input type="hidden" name="appointment_date" :value="date">
                <input type="hidden" name="appointment_time" :value="selectedTime">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="patient_name"
                           value="{{ old('patient_name') }}"
                           placeholder="Contoh: Budi Santoso"
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-sky-500 transition-colors text-base @error('patient_name') border-red-400 @enderror"
                           required>
                    @error('patient_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium text-sm">+62</span>
                        <input type="tel" name="patient_phone"
                               value="{{ old('patient_phone') }}"
                               placeholder="812-3456-7890"
                               class="w-full border-2 border-slate-200 rounded-xl pl-14 pr-4 py-3 text-slate-800 focus:outline-none focus:border-sky-500 transition-colors text-base @error('patient_phone') border-red-400 @enderror"
                               required>
                    </div>
                    <p class="text-slate-400 text-xs mt-1">Konfirmasi booking dikirim ke nomor ini</p>
                    @error('patient_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keluhan (opsional)</label>
                    <textarea name="notes" rows="2"
                              placeholder="Ceritakan keluhan singkat..."
                              class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-sky-500 transition-colors text-base resize-none">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" id="btn-booking"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl transition-all duration-200 text-base flex items-center justify-center gap-2 active:scale-95 shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Booking Sekarang
                </button>

                <p class="text-center text-slate-400 text-xs">
                    Konfirmasi akan dikirim via WhatsApp segera setelah booking
                </p>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        step: 1,
        doctorId: '',
        doctorName: '',
        date: '',
        slots: [],
        selectedTime: '',
        loadingSlots: false,
        scheduleExists: false,
        today: new Date().toISOString().split('T')[0],
        maxDate: (() => {
            const d = new Date(); d.setDate(d.getDate() + 30);
            return d.toISOString().split('T')[0];
        })(),

        async goStep2() {
            if (!this.doctorId || !this.date) return;
            this.step = 2;
            this.loadingSlots = true;
            this.slots = [];
            this.scheduleExists = false;

            try {
                const res = await fetch(`/booking/slots?doctor_id=${this.doctorId}&date=${this.date}`);
                const data = await res.json();
                this.slots = data.slots || [];
                this.scheduleExists = data.schedule_exists || false;
            } catch (e) {
                this.slots = [];
            } finally {
                this.loadingSlots = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
        },
    }
}
</script>
@endpush
