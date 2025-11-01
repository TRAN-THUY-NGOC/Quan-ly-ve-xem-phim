<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Showtime, Movie, Room};
use Carbon\Carbon;

class ShowtimesController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['movie', 'room'])
            ->orderByDesc('start_time')
            ->paginate(12);

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        $movies = Movie::orderBy('title')->get(['id','title','duration']);
        $rooms  = Room::orderBy('name')->get(['id','name']);
        $showtime = new Showtime();
        return view('admin.showtimes.form', compact('showtime','movies','rooms'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time'   => 'nullable|date|after:start_time',
        ]);

        if (empty($data['end_time'])) {
            $movie = Movie::find($data['movie_id']);
            $duration = (int)($movie->duration ?? 120);
            $data['end_time'] = Carbon::parse($data['start_time'])->addMinutes($duration);
        }

        Showtime::create($data);
        return redirect()->route('admin.showtimes.index')->with('ok','Đã thêm suất chiếu.');
    }

    public function edit(Showtime $showtime)
    {
        $movies = Movie::orderBy('title')->get(['id','title']);
        $rooms  = Room::orderBy('name')->get(['id','name']);
        return view('admin.showtimes.form', compact('showtime','movies','rooms'));
    }

    public function update(Request $r, Showtime $showtime)
    {
        $data = $r->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time'   => 'nullable|date|after:start_time',
        ]);

        if (empty($data['end_time'])) {
            $movie = Movie::find($data['movie_id']);
            $duration = (int)($movie->duration ?? 120);
            $data['end_time'] = Carbon::parse($data['start_time'])->addMinutes($duration);
        }

        $showtime->update($data);
        return redirect()->route('admin.showtimes.index')->with('ok','Cập nhật thành công.');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return back()->with('ok','Đã xoá suất chiếu.');
    }
}
