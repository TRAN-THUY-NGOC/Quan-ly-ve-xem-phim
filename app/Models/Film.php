<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = 'films'; // tên bảng trong database
    protected $fillable = [
        'ten_phim',
        'the_loai_id',
        'thoi_luong',
        'mo_ta',
        'ngay_khoi_chieu',
        'hinh_anh'
    ];

    public function theLoai()
    {
        return $this->belongsTo(TheLoai::class, 'the_loai_id');
    }
}
