<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'patient_name',
        'patient_phone',
        'appointment_date',
        'appointment_time',
        'queue_number',
        'status',
        'wa_sent_at',
        'reminder_h1_sent_at',
        'reminder_h2jam_sent_at',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'wa_sent_at' => 'datetime',
        'reminder_h1_sent_at' => 'datetime',
        'reminder_h2jam_sent_at' => 'datetime',
    ];

    const STATUS_SCHEDULED  = 'scheduled';
    const STATUS_CONFIRMED  = 'confirmed';
    const STATUS_DONE       = 'done';
    const STATUS_CANCELLED  = 'cancelled';

    const STATUS_LABELS = [
        'scheduled'  => 'Terjadwal',
        'confirmed'  => 'Dikonfirmasi',
        'done'       => 'Selesai',
        'cancelled'  => 'Dibatalkan',
    ];

    const STATUS_COLORS = [
        'scheduled'  => 'blue',
        'confirmed'  => 'green',
        'done'       => 'gray',
        'cancelled'  => 'red',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getFormattedTimeAttribute(): string
    {
        return \Carbon\Carbon::parse($this->appointment_time)->format('H:i');
    }

    public function getFormattedDateAttribute(): string
    {
        return \Carbon\Carbon::parse($this->appointment_date)->locale('id')->translatedFormat('l, d F Y');
    }

    /**
     * Today's appointments scope
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Scope for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('appointment_date', $date);
    }
}
