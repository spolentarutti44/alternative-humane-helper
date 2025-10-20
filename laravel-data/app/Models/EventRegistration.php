<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_name',
        'attendee_email',
        'attendee_phone',
        'registration_date',
        'attendance_status',
        'notes',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('attendance_status', 'registered');
    }

    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    public function markAttended()
    {
        $this->update(['attendance_status' => 'attended']);
    }

    public function cancel()
    {
        $this->update(['attendance_status' => 'cancelled']);
    }
}

