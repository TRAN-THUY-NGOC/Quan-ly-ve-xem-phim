<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Nếu có upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            // Lưu ảnh mới
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $user->avatar = $avatarName;
        }

        // Cập nhật các thông tin khác
        $user->update([
            'name' => $request->name,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
