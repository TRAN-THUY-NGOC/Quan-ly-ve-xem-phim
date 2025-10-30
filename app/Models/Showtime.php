<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'room_id',
        'start_time',
        'end_time'
    ];

    // Liên kết đến phim
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'film_id');
    }
}
