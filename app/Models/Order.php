<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'total_amount', // phải có dòng này
    'status',
];

    // Nếu bảng không phải 'orders', thêm dòng này:
    // protected $table = 'orders';
}
