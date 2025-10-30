<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';
    public $timestamps = false;

    protected $fillable = ['room_id', 'seat_type_id', 'row_letter', 'seat_number', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }
}
