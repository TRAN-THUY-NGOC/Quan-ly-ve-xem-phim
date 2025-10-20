<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheLoai extends Model
{
    use HasFactory;

    protected $table = 'the_loai';
    protected $fillable = ['ten_the_loai'];

    public function films()
    {
        return $this->hasMany(Film::class, 'the_loai_id');
    }
}
