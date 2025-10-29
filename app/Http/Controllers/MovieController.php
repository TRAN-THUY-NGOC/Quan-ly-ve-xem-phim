<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        // Lọc phim đang chiếu hoặc sắp chiếu
        $type = $request->query('type', 'now_showing'); // mặc định là phim đang chiếu

        if ($type === 'coming_soon') {
            $movies = Movie::where('release_date', '>', now())
                ->where('is_active', 1)
                ->orderBy('release_date', 'asc')
                ->get();
        } else {
            $movies = Movie::where('release_date', '<=', now())
                ->where('is_active', 1)
                ->orderBy('release_date', 'desc')
                ->get();
        }

        return view('user.dashboard', compact('movies', 'type'));
    }
}
