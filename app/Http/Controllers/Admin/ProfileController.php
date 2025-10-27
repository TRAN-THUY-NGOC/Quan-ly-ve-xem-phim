<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Lấy thông tin admin hiện tại
        $admin = auth('admin')->user();

        // Gửi qua view dashboard.blade.php
        return view('admin.dashboard', compact('admin'));
    }
}
