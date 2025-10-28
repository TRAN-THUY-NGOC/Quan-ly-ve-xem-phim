<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;

class FilmController extends Controller
{
    public function index()
    {
        // Lấy danh sách phim từ database
        $films = Film::paginate(10);

        // Trả về view admin/films/index.blade.php
        return view('admin.films.index', compact('films'));
    }
}
