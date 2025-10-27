<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phim;
use Illuminate\Support\Facades\Storage;

class PhimController extends Controller
{
    // Danh sách phim
    public function index()
    {
        $phims = Phim::paginate(10);
        return view('admin.phim.index', compact('phims'));
    }

    // Form thêm
    public function create()
    {
        return view('admin.phim.create');
    }

    // Xử lý thêm
    public function store(Request $request)
    {
        $request->validate([
            'tenphim' => 'required',
            'theloai' => 'required',
            'ngaychieu' => 'required|date',
            'poster' => 'nullable|image|max:2048',
            'trailer' => 'nullable|mimes:mp4,mov,avi|max:10000'
        ]);

        $posterPath = $request->file('poster')?->store('posters', 'public');
        $trailerPath = $request->file('trailer')?->store('trailers', 'public');

        Phim::create([
            'tenphim' => $request->tenphim,
            'theloai' => $request->theloai,
            'thoiluong' => $request->thoiluong,
            'ngaychieu' => $request->ngaychieu,
            'poster' => $posterPath,
            'trailer' => $trailerPath,
            'trangthai' => 'Đang chiếu'
        ]);

        return redirect()->route('admin.phim.index')->with('success', 'Thêm phim thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $phim = Phim::findOrFail($id);
        return view('admin.phim.edit', compact('phim'));
    }

    // Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $phim = Phim::findOrFail($id);

        $posterPath = $phim->poster;
        $trailerPath = $phim->trailer;

        if ($request->hasFile('poster')) {
            if ($posterPath) Storage::disk('public')->delete($posterPath);
            $posterPath = $request->file('poster')->store('posters', 'public');
        }
        if ($request->hasFile('trailer')) {
            if ($trailerPath) Storage::disk('public')->delete($trailerPath);
            $trailerPath = $request->file('trailer')->store('trailers', 'public');
        }

        $phim->update([
            'tenphim' => $request->tenphim,
            'theloai' => $request->theloai,
            'thoiluong' => $request->thoiluong,
            'ngaychieu' => $request->ngaychieu,
            'poster' => $posterPath,
            'trailer' => $trailerPath,
            'gia' => $request->gia
        ]);

        return redirect()->route('admin.phim.index')->with('success', 'Cập nhật thành công!');
    }

    // Xóa phim
    public function destroy($id)
    {
        $phim = Phim::findOrFail($id);
        if ($phim->poster) Storage::disk('public')->delete($phim->poster);
        if ($phim->trailer) Storage::disk('public')->delete($phim->trailer);
        $phim->delete();

        return redirect()->route('admin.phim.index')->with('success', 'Xóa phim thành công!');
    }
}
