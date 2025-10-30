<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';

    protected $fillable = [
        'title','genre','duration_min','release_date',
        'description','poster_url','trailer_url','is_active',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_active'    => 'boolean',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'movie_id');
    }
}
