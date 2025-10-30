<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.'])
                         ->onlyInput('email');
        }

        $request->session()->regenerate();

        // ƯU TIÊN intended() nếu có
        $intended = redirect()->intended()->getTargetUrl();
        if ($intended && $intended !== url('/')) {
            return redirect()->intended();
        }

        $user = Auth::user();
        // Điều hướng theo vai trò
        if (optional($user->role)->name === 'Admin' || $user->role_id === 1) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
