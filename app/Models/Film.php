<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'films';

    protected $fillable = [
        'film_code',
        'title',
        'genre',
        'director',
        'cast',
        'country',
        'language',
        'duration_min',
        'release_date',
        'ticket_price',
        'status',
        'description',
        'image',
        'poster_url',
        'trailer_url',
        'is_active',
    ];

    public $timestamps = true;
}
