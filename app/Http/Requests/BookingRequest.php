<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id'        => ['required', 'uuid', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'patient_name'     => ['required', 'string', 'min:2', 'max:100'],
            'patient_phone'    => ['required', 'string', 'min:9', 'max:20'],
            'notes'            => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required'        => 'Pilih dokter terlebih dahulu.',
            'doctor_id.exists'          => 'Dokter tidak ditemukan.',
            'appointment_date.required' => 'Tanggal appointment wajib diisi.',
            'appointment_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'appointment_time.required' => 'Pilih waktu appointment.',
            'patient_name.required'     => 'Nama pasien wajib diisi.',
            'patient_phone.required'    => 'Nomor WhatsApp wajib diisi.',
            'patient_phone.min'         => 'Nomor WhatsApp tidak valid.',
        ];
    }
}
