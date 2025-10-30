<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class UsersController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');
        $users = User::query()
            ->when($q, fn($qq)=>$qq->where(function($w) use($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users','q'));
    }

    public function create()
    {
        return view('admin.users.form', ['user'=>new User()]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['nullable','regex:/^[0-9]{8,15}$/','unique:users,phone'],
            'password' => ['required','string','min:6'],
            'role_id'  => ['required','integer'], // 1=Admin, 2=Customer (ví dụ)
        ]);
        $data['password'] = bcrypt($data['password']);

        User::create($data);
        return redirect()->route('admin.users.index')->with('ok','Tạo tài khoản thành công');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $r, User $user)
    {
        $data = $r->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone' => ['nullable','regex:/^[0-9]{8,15}$/', Rule::unique('users','phone')->ignore($user->id)],
            'role_id'  => ['required','integer'],
            'password' => ['nullable','string','min:6'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('ok','Cập nhật thành công');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('ok','Đã xoá tài khoản');
    }

    public function resetPassword(Request $request, User $user)
    {
        // chỉ cho reset người khác, tùy bạn muốn chặn hay không:
        // if ($user->id === auth()->id()) return back()->withErrors('Không thể tự reset mật khẩu của chính bạn.');

        // Mặc định = ngày sinh ddmmyyyy nếu có birthday
        $plain = null;
        if (!empty($user->birthday)) {
            try {
                $plain = Carbon::parse($user->birthday)->format('dmY'); // ví dụ 05/09/2003 -> 05092003
            } catch (\Throwable $e) {
                // nếu parse lỗi → dùng mật khẩu tạm
            }
        }
        if (!$plain) {
            $plain = Str::upper(Str::random(8)); // fallback
        }

        $user->password = Hash::make($plain);
        $user->save();

        // Có thể ép đổi mật khẩu lần sau bằng cờ, nếu bạn có cột `must_change_password`:
        // $user->forceFill(['must_change_password' => 1])->save();

        return back()->with('ok', "Đã reset mật khẩu cho {$user->name}. Mật khẩu mới: {$plain}");
        // Nếu bạn lo vấn đề an toàn, có thể chỉ hiển thị: "đã reset theo ngày sinh" thay vì show plaintext
    }
}
