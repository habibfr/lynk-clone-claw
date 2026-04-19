<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create Sample Doctors
        $doc1 = Doctor::create([
            'name' => 'dr. Budi Santoso',
            'specialization' => 'Dokter Umum',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $doc2 = Doctor::create([
            'name' => 'dr. Siti Aminah, Sp.A',
            'specialization' => 'Spesialis Anak',
            'phone' => '081298765432',
            'is_active' => true,
        ]);

        // 3. Create Sample Schedules for docs
        // dr. Budi
        for ($i = 1; $i <= 5; $i++) { // Senin - Jumat
            Schedule::create([
                'doctor_id' => $doc1->id,
                'day_of_week' => $i,
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'slot_duration_minutes' => 15,
                'max_patients' => 16,
            ]);
            Schedule::create([
                'doctor_id' => $doc1->id,
                'day_of_week' => $i,
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'slot_duration_minutes' => 15,
                'max_patients' => 12,
            ]);
        }

        // dr. Siti
        for ($i = 2; $i <= 6; $i += 2) { // Selasa, Kamis, Sabtu
            Schedule::create([
                'doctor_id' => $doc2->id,
                'day_of_week' => $i,
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'slot_duration_minutes' => 20,
                'max_patients' => 12,
            ]);
        }

        $this->command->info('Database seeded! Admin login: admin@klinik.com / password123');
    }
}
