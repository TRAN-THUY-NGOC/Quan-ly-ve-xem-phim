<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShowtimePrice extends Model
{
    use HasFactory;

    protected $table = 'showtime_prices';

    protected $fillable = [
        'showtime_id',
        'seat_type_id',
        'price_modifier',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtime_id');
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class, 'seat_type_id');
    }
}
