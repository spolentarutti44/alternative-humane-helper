<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'amount',
        'donation_type',
        'payment_method',
        'transaction_id',
        'status',
        'donation_date',
        'tax_receipt_sent',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'date',
        'tax_receipt_sent' => 'boolean',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('donation_date', [$startDate, $endDate]);
    }

    public function scopeMonetary($query)
    {
        return $query->where('donation_type', 'monetary');
    }

    public function markReceiptSent()
    {
        $this->update(['tax_receipt_sent' => true]);
    }
}

