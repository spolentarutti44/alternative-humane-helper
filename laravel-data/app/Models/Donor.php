<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'is_recurring',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function completedDonations()
    {
        return $this->hasMany(Donation::class)
            ->where('status', 'completed');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTotalDonatedAttribute()
    {
        return $this->completedDonations()->sum('amount');
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }
}

