<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    // GET /movies, /dashboard, /user/dashboard
    public function index()
    {
        $movies = Movie::where('is_active', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        // nếu chưa có view, tạm trả chuỗi cho test
        // return response()->json($movies);

        return view('movies.index', compact('movies'));
    }

    // GET /movies/{movie}
    public function show(Movie $movie)
    {
        return view('movies.show', compact('movie'));
    }
}
