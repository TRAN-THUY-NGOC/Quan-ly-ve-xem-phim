<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'code',
        'name',
        'cinema_id',
        'seat_count',
        'status',
    ];

    // Mỗi phòng thuộc 1 rạp
    public function cinema()
    {
        return $this->belongsTo(Cinema::class, 'cinema_id');
    }

    // 1 phòng có nhiều ghế
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // Lấy danh sách loại ghế có trong phòng
    public function seatTypes()
    {
        return $this->hasManyThrough(
            SeatType::class,
            Seat::class,
            'room_id',
            'id',
            'id',
            'seat_type_id'
        );
    }
}
