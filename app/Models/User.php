<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    // 🔒 Tự động mã hóa mật khẩu khi set
    public function setPasswordAttribute($value)
    {
        // Nếu đã là bcrypt ($2y$) thì giữ nguyên, còn lại thì băm
        $this->attributes['password'] =
            Str::startsWith((string)$value, '$2y$') ? $value : Hash::make($value);
    }

    // Nếu có liên kết Role thì thêm:
    public function role()
    {
        return $this->belongsTo(Role::class);
    }



}
