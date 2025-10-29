<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'genre', 'duration_min', 'release_date',
        'description', 'poster_url', 'trailer_url', 'is_active'
    ];
}
