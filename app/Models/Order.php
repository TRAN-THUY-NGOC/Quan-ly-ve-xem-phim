<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'email', 'phone'
    ];

    // Một đơn hàng có nhiều vé
    public function tickets()
    {
        // Nhiều tickets thông qua bảng order_details
        return $this->belongsToMany(Ticket::class, 'order_details', 'order_id', 'ticket_id');
    }

    // Một đơn hàng thuộc về người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
