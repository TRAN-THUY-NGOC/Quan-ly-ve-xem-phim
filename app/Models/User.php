<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'birthday',
        'address',
        'avatar',
    ];

    // 🔒 Tự động mã hóa mật khẩu khi set
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // Nếu password chưa mã hóa thì mã hóa
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    // Nếu có liên kết Role thì thêm:
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
