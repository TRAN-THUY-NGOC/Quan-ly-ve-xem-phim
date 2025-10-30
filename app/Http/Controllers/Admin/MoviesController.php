<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MoviesController extends Controller
{
    public function index()
    {
        $movies = Movie::orderByDesc('created_at')->paginate(12);
        // return view('admin.movies.index', compact('movies'));
        return response('admin.movies.index (demo) '. $movies->count().' rows');
    }

    public function create()
    {
        $movie = new Movie(['is_active' => 1]);
        // return view('admin.movies.form', compact('movie'));
        return response('admin.movies.create (demo)');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:255',
            'genre'        => 'required|string|max:100',
            'duration_min' => 'required|integer|min:1|max:600',
            'release_date' => 'required|date',
            'trailer_url'  => 'nullable|url|max:255',
            'poster_url'   => 'nullable|url|max:255',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);
        $data['is_active'] = $r->boolean('is_active');
        Movie::create($data);
        return redirect()->route('admin.movies.index')->with('ok', 'Thêm phim thành công.');
    }

    public function edit(Movie $movie)
    {
        // return view('admin.movies.form', compact('movie'));
        return response('admin.movies.edit (demo) id='.$movie->id);
    }

    public function update(Request $r, Movie $movie)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:255',
            'genre'        => 'required|string|max:100',
            'duration_min' => 'required|integer|min:1|max:600',
            'release_date' => 'required|date',
            'trailer_url'  => 'nullable|url|max:255',
            'poster_url'   => 'nullable|url|max:255',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $movie->update($data);
        return redirect()->route('admin.movies.index')->with('ok', 'Cập nhật phim thành công.');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return back()->with('ok', 'Đã xóa phim.');
    }
}
