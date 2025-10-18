<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = ['film_id', 'cinema_id', 'room_id', 'date', 'time'];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
