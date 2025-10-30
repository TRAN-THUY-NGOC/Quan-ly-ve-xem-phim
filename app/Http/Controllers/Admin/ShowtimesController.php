<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShowtimesController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['movie','room'])
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        return view('admin.showtimes.form', [
            'showtime' => new Showtime(),
            'movies'   => Movie::orderBy('title')->get(),
            'rooms'    => Room::orderBy('name')->get(),
            'mode'     => 'create',
        ]);
    }

    public function store(Request $r)
    {
        $r->validate([
            'movie_id'   => ['required', Rule::exists('movies','id')],
            'room_id'    => ['required', Rule::exists('rooms','id')],
            'start_time' => ['required','date'],
        ]);

        // Tính end_time theo duration_min của phim
        $movie = Movie::findOrFail($r->movie_id);
        $start = Carbon::parse($r->start_time);
        $end   = (clone $start)->addMinutes((int)$movie->duration_min);

        // Kiểm tra chồng lấn lịch trong cùng phòng
        $overlap = Showtime::where('room_id', $r->room_id)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_time','<=',$start)->where('end_time','>=',$end);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors('Khung giờ đã có suất chiếu khác trong phòng này.')
                ->withInput();
        }

        Showtime::create([
            'movie_id'   => $r->movie_id,
            'room_id'    => $r->room_id,
            'start_time' => $start,
            'end_time'   => $end,
        ]);

        return redirect()->route('admin.showtimes.index')->with('ok','Đã tạo suất chiếu.');
    }

    public function edit(Showtime $showtime)
    {
        return view('admin.showtimes.form', [
            'showtime' => $showtime,
            'movies'   => Movie::orderBy('title')->get(),
            'rooms'    => Room::orderBy('name')->get(),
            'mode'     => 'edit',
        ]);
    }

    public function update(Request $r, Showtime $showtime)
    {
        $r->validate([
            'movie_id'   => ['required', Rule::exists('movies','id')],
            'room_id'    => ['required', Rule::exists('rooms','id')],
            'start_time' => ['required','date'],
        ]);

        $movie = Movie::findOrFail($r->movie_id);
        $start = Carbon::parse($r->start_time);
        $end   = (clone $start)->addMinutes((int)$movie->duration_min);

        $overlap = Showtime::where('room_id', $r->room_id)
            ->where('id','<>', $showtime->id)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_time','<=',$start)->where('end_time','>=',$end);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors('Khung giờ đã có suất chiếu khác trong phòng này.')
                ->withInput();
        }

        $showtime->update([
            'movie_id'   => $r->movie_id,
            'room_id'    => $r->room_id,
            'start_time' => $start,
            'end_time'   => $end,
        ]);

        return redirect()->route('admin.showtimes.index')->with('ok','Đã cập nhật suất chiếu.');
    }

    public function destroy(Showtime $showtime)
    {
        // Nếu đã bán vé thì không cho xóa (tuỳ logic)
        $hasTickets = $showtime->tickets()->exists();
        if ($hasTickets) {
            return back()->withErrors('Suất chiếu đã có vé, không thể xoá.');
        }

        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('ok','Đã xoá suất chiếu.');
    }
}
