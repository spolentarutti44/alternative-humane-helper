<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'gender',
        'status',
        'description',
        'intake_date',
        'medical_notes',
        'photo_url',
    ];

    protected $casts = [
        'intake_date' => 'date',
        'age' => 'integer',
    ];

    public function fosterAssignments()
    {
        return $this->hasMany(FosterAssignment::class);
    }

    public function currentFosterAssignment()
    {
        return $this->hasOne(FosterAssignment::class)
            ->where('status', 'active')
            ->latest();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFostered($query)
    {
        return $query->where('status', 'fostered');
    }

    public function scopeAdopted($query)
    {
        return $query->where('status', 'adopted');
    }
}

