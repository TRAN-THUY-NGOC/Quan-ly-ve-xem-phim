<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'amount',
        'payment_method',
        'transaction_code',
        'status',
        'paid_at'
    ];

    // ✅ Mỗi payment thuộc về một booking (ticket_id → bookings.id)
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'ticket_id');
    }
}
