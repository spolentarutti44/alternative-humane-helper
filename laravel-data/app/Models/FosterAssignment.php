<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FosterAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'foster_id',
        'animal_id',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function foster()
    {
        return $this->belongsTo(Foster::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'end_date' => now(),
        ]);
        
        $this->animal->update(['status' => 'available']);
    }
}

