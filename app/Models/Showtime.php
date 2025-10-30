<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'date',
        'price',
        'start_time',
        'end_time',
        'cinema',
        'room',
        'total_seats',
        'available_seats',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}
