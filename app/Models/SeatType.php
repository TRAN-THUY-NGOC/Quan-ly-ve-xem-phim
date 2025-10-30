<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $table = 'seat_types';

    protected $fillable = [
        'name',        // Tên loại ghế (Thường, VIP, Đôi,...)
        'base_price'   // Giá cơ bản cho loại ghế
    ];

    // ✅ Một loại ghế có thể xuất hiện ở nhiều ghế trong nhiều phòng
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
