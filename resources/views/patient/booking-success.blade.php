@extends('layouts.patient')
@section('title', 'Booking Berhasil')

@section('content')
<div class="space-y-5">

    {{-- Success Card --}}
    <div id="ticket-card" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Green Header --}}
        <div class="bg-gradient-to-br from-green-400 to-emerald-600 p-8 text-center text-white">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <svg class="w-10 h-10 text-white" style="width:40px; height:40px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold">Booking Berhasil!</h2>
            <p class="text-green-100 mt-1 text-sm">Konfirmasi dikirim ke WhatsApp Anda</p>
        </div>

        {{-- Ticket --}}
        <div class="p-5">
            {{-- Queue Number --}}
            <div class="text-center mb-6">
                <p class="text-slate-500 text-sm mb-1">Nomor Antrian</p>
                <div class="mx-auto w-24 h-24 rounded-full bg-sky-50 border-4 border-sky-500 text-center" style="line-height: 5.5rem;">
                    <span class="text-4xl font-black text-sky-600 inline-block align-middle">{{ $appointment->queue_number }}</span>
                </div>
            </div>

            {{-- Divider dashed --}}
            <div class="border-t-2 border-dashed border-slate-200 my-5 relative">
                <div class="absolute -left-5 -top-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200"></div>
                <div class="absolute -right-5 -top-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200"></div>
            </div>

            {{-- Detail --}}
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-sky-500" style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nama Pasien</p>
                        <p class="font-semibold text-slate-800">{{ $appointment->patient_name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-500" style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Dokter</p>
                        <p class="font-semibold text-slate-800">{{ $appointment->doctor->name }}</p>
                        @if($appointment->doctor->specialization)
                        <p class="text-xs text-purple-500">{{ $appointment->doctor->specialization }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-500" style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Tanggal & Waktu</p>
                        <p class="font-semibold text-slate-800">{{ $appointment->formatted_date }}</p>
                        <p class="text-sky-600 font-semibold">{{ $appointment->formatted_time }} WIB</p>
                    </div>
                </div>
            </div>

            {{-- Divider dashed --}}
            <div class="border-t-2 border-dashed border-slate-200 my-5 relative">
                <div class="absolute -left-5 -top-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200"></div>
                <div class="absolute -right-5 -top-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200"></div>
            </div>

            {{-- Info --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex gap-2">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" style="width:16px; height:16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-amber-700 text-xs leading-relaxed">
                        Harap datang <strong>10 menit sebelum jadwal</strong>. Tunjukkan nomor antrian ini kepada resepsionis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="space-y-3">
        <button id="btn-download" onclick="downloadTicket()"
                class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white font-semibold py-4 rounded-xl transition-all duration-200 active:scale-95 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Download Bukti (PDF)
        </button>

        <a href="{{ route('booking') }}"
           class="block w-full text-center bg-sky-500 hover:bg-sky-600 text-white font-semibold py-4 rounded-xl transition-all duration-200 active:scale-95">
            Booking Lagi
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadTicket() {
        const ticket = document.getElementById('ticket-card');
        const btn = document.getElementById('btn-download');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Sedang Mengunduh...';
        btn.disabled = true;

        const opt = {
            margin:       [10, 10, 10, 10], // auto margin
            filename:     'Tiket-Antrian-{{ $appointment->queue_number }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { 
                scale: 2, 
                useCORS: true, 
                backgroundColor: '#ffffff',
                scrollY: 0,
                scrollX: 0,
                windowWidth: 600,
                onclone: function (doc) {
                    // Paksa lebar standar saat di-_clone_ agar tidak gepeng di mobile
                    const clonedTicket = doc.getElementById('ticket-card');
                    if(clonedTicket) {
                        clonedTicket.style.width = '600px';
                        clonedTicket.style.margin = '0 auto';
                        // Hapus animasi bounce karena canvas tidak bisa merender animasi
                        const animated = clonedTicket.querySelector('.animate-bounce');
                        if (animated) animated.classList.remove('animate-bounce');
                    }
                }
            },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(ticket).save().then(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => {
            alert('Gagal mengunduh PDF.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endpush
