<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'row_letter',
        'seat_number',
        'seat_type_id',
    ];

    // Mỗi ghế thuộc về một phòng chiếu
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Mỗi ghế thuộc về một loại ghế (Thường / VIP)
    public function seatType()
    {
        return $this->belongsTo(SeatType::class, 'seat_type_id');
    }

    // Mỗi ghế có thể có nhiều lượt đặt
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'seat_id');
    }
}
