<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('doctor_id')->constrained('doctors');
            $table->string('patient_name');
            $table->string('patient_phone');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('queue_number', 10);
            $table->enum('status', ['scheduled', 'confirmed', 'done', 'cancelled'])->default('scheduled');
            $table->timestamp('wa_sent_at')->nullable();
            $table->timestamp('reminder_h1_sent_at')->nullable();
            $table->timestamp('reminder_h2jam_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Prevent double booking
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'unique_booking_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
