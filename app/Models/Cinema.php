<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    protected $table = 'cinemas';

    protected $fillable = ['name', 'address', 'city', 'hotline', 'status'];

    // Một rạp có thể có nhiều phòng chiếu
    public function rooms()
    {
        return $this->hasMany(Room::class, 'cinema_id');
    }
}
