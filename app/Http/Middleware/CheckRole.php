<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Kiểm tra quyền truy cập của user.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Nếu không đúng vai trò yêu cầu
        if ($user->role !== $role) {
            // Nếu user là admin → cho về admin dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn đang đăng nhập với tư cách admin.');
            }

            // Còn lại → cho về user dashboard
            return redirect()->route('user.dashboard')
                ->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        // Nếu đúng role → cho đi tiếp
        return $next($request);
    }
}
