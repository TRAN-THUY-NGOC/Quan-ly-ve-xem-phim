<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';

    protected $fillable = [
        'title', 'genre', 'duration_min', 'release_date',
        'description', 'poster_url', 'trailer_url', 'is_active',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_active'    => 'boolean',
    ];
}
