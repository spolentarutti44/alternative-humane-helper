<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'registration_required',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'capacity' => 'integer',
        'registration_required' => 'boolean',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function confirmedRegistrations()
    {
        return $this->hasMany(EventRegistration::class)
            ->where('attendance_status', 'registered');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
            ->where('status', 'published');
    }

    public function hasCapacity()
    {
        if (!$this->capacity) {
            return true;
        }
        
        return $this->confirmedRegistrations()->count() < $this->capacity;
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->capacity) {
            return null;
        }
        
        return $this->capacity - $this->confirmedRegistrations()->count();
    }
}

