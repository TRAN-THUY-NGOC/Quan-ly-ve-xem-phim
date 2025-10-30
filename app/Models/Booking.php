<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'showtime_id',
        'seat_id',
        'status',
        'payment_status',
        'total_price'
    ];

    // Một vé thuộc về một phim
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Một vé thuộc về một suất chiếu
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    // Một vé thuộc về một ghế
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // Một vé thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Một vé có thể có 1 thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class, 'ticket_id', 'id');
    }
}
