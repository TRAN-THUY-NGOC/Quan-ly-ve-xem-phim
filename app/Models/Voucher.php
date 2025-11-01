<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        'code', 'type', 'value',
        'start_at', 'end_at',
        'usage_limit', 'used_count',
        'status', 'meta',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'meta'     => 'array',
    ];

    public function isActive(): bool
    {
        $now = now();
        if ((int)$this->status !== 1) return false;
        if ($this->start_at && $this->start_at->gt($now)) return false;
        if ($this->end_at && $this->end_at->lt($now)) return false;
        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) return false;
        return true;
    }
}
