<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Kiểm tra quyền truy cập của user.
     *
     * @param  string  $role Tên vai trò yêu cầu (ví dụ: 'Admin' hoặc 'Customer')
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        // 1. Nếu chưa đăng nhập: Chuyển hướng đến trang Login
        if (!$user) {
            return redirect()->route('login');
        }

        // --- LẤY TÊN VAI TRÒ CHÍNH XÁC ---
        // Lấy tên vai trò từ mối quan hệ Model (user->role->name)
        // Nếu user->role không tồn tại (lỗi FK), mặc định là 'Guest'
        $userRoleName = $user->role->name ?? 'Guest'; 
        
        // Chuẩn hóa tên role yêu cầu và tên role của user (lowercase để dễ so sánh)
        $requiredRole = strtolower($role);
        $currentUserRole = strtolower($userRoleName);


        // 2. Nếu không đúng vai trò yêu cầu
        if ($currentUserRole !== $requiredRole) {
            
            // Xử lý chuyển hướng khi không có quyền (Quan trọng: Tránh vòng lặp)
            
            // Nếu user là admin (hoặc đã đăng nhập) và cố gắng truy cập trang user:
            if ($currentUserRole === 'admin') {
                // Chuyển Admin về Dashboard của Admin
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn đang đăng nhập với tư cách Quản trị viên và không thể truy cập trang Khách hàng.');
            }

            // Nếu user là khách hàng và cố gắng truy cập trang admin:
            if ($currentUserRole === 'customer' || $currentUserRole === 'guest') {
                // Chuyển Customer về trang Chủ/Dashboard của Customer
                return redirect()->route('user.dashboard')
                    ->with('error', 'Bạn không có quyền truy cập trang này.');
            }
            
            // Mọi trường hợp còn lại (lỗi không xác định)
            return redirect('/');
        }

        // 3. Nếu đúng role: Cho phép truy cập
        return $next($request);
    }
}