<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone' => ['nullable','regex:/^[0-9]{8,15}$/', Rule::unique('users','phone')->ignore($user->id)],
        ]);

        $user->fill($request->only('name','email','phone'))->save();

        return back()->with('success','Cập nhật thành công.');
    }
}
