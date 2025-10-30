<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code','type','value','max_discount_amount','min_order_amount',
        'start_date','end_date','usage_limit','times_used','is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
        'value'               => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount'    => 'decimal:2',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'voucher_id');
    }
}
