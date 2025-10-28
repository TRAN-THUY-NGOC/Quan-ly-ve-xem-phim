<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Xóa avatar cũ
            if ($user->avatar && Storage::exists('avatars/' . $user->avatar)) {
                Storage::delete('avatars/' . $user->avatar);
            }

            // Upload avatar mới
            $file->storeAs('avatars', $fileName);

            $user->avatar = $fileName;
        }

        // Update các thông tin khác
        $user->name = $request->name;
        $user->birthday = $request->birthday;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Cập nhật thành công!');
    }

    public function editInfo()
    {
        return view('admin.update-info');
    }

    public function info()
    {
        return view('admin.dashboard');
    }
}
