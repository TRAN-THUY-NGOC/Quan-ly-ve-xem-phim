<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'films';

    protected $fillable = [
        'title',
        'genre',
        'duration_min',
        'release_date',
        'description',
        'poster_url',
        'trailer_url',
        'is_active'
    ];
}
