<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\TheLoai;

class FilmController extends Controller
{
    // Trang tra cứu phim
    public function index(Request $request)
    {
        $query = Film::query()->with('theLoai');

        if ($request->filled('ten')) {
            $query->where('ten_phim', 'like', '%' . $request->ten . '%');
        }

        if ($request->filled('the_loai')) {
            $query->where('the_loai_id', $request->the_loai);
        }

        if ($request->filled('ngay_khoi_chieu')) {
            $query->whereDate('ngay_khoi_chieu', $request->ngay_khoi_chieu);
        }

        $films = $query->get();
        $genres = TheLoai::all();

        return view('phim.index', compact('films', 'genres'));
    }

    // Trang chi tiết phim
    public function show($id)
    {
        $film = Film::with('theLoai')->findOrFail($id);
        return view('phim.show', compact('film'));
    }
}
