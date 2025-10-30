<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    // Nếu table mặc định 'tickets' thì không cần $table.
    // protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'voucher_id',
        'showtime_id',
        'seat_id',
        'qr_code',
        'final_price',
        'discount_amount',
        'membership_discount_rate',
        'status',                // booked, canceled, used
        'points_earned',
    ];

    protected $casts = [
        'final_price'               => 'decimal:2',
        'discount_amount'           => 'decimal:2',
        'membership_discount_rate'  => 'decimal:4',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
    ];

    /* -----------------------------
     | Relationships
     * ----------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /* -----------------------------
     | Query Scopes
     * ----------------------------*/

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    public function scopeForShowtimeSeat($query, int $showtimeId, int $seatId)
    {
        return $query->where('showtime_id', $showtimeId)
                     ->where('seat_id', $seatId);
    }

    public function scopeOwnedBy($query, ?int $userId)
    {
        return $userId ? $query->where('user_id', $userId) : $query->whereNull('user_id');
    }

    /* -----------------------------
     | Helpers (nghiệp vụ đơn giản)
     * ----------------------------*/

    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function markUsed(): bool
    {
        if ($this->isBooked()) {
            $this->status = 'used';
            return $this->save();
        }
        return false;
    }

    public function markCanceled(): bool
    {
        if ($this->isBooked()) {
            $this->status = 'canceled';
            return $this->save();
        }
        return false;
    }

    // Gợi ý: nếu muốn tự sinh QR khi tạo (nếu thiếu), bật events:
    protected static function booted()
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->qr_code)) {
                $ticket->qr_code = 'QR'.bin2hex(random_bytes(10));
            }
        });
    }
}
