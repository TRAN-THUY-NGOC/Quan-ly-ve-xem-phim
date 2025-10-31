<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MovieController extends Controller
{
    /**
     * Danh sách phim cho khách:
     * - filter: status=now|upcoming (đang chiếu / sắp chiếu)
     * - q: từ khoá theo tên
     * - genre: thể loại (nếu có cột/quan hệ)
     * Giả định movies có cột: title, poster_url, release_date (DATE), duration, trailer_url, description
     */
    public function index(Request $r)
    {
        $q       = trim($r->query('q', ''));
        $status  = $r->query('status'); // now|upcoming|null
        $today   = Carbon::today();

        $movies = DB::table('movies')
            ->when($q, fn($t) => $t->where('title', 'like', "%$q%"))
            ->when($status === 'now', function ($t) use ($today) {
                // Đang chiếu: có ít nhất 1 suất chiếu từ hôm nay trở đi
                $t->whereExists(function ($q) use ($today) {
                    $q->from('showtimes')->whereColumn('showtimes.movie_id', 'movies.id')
                      ->whereDate('start_time', '>=', $today->toDateString());
                });
            })
            ->when($status === 'upcoming', function ($t) use ($today) {
                // Sắp chiếu: ngày phát hành sau hôm nay (hoặc chưa có suất)
                $t->whereDate('release_date', '>', $today->toDateString());
            })
            ->orderByDesc('release_date')
            ->paginate(12)
            ->withQueryString();

        return view('movies.index', compact('movies', 'q', 'status'));
    }

    /**
     * Trang chi tiết phim + các suất theo ngày chọn
     */
    public function show($movieId, Request $r)
    {
        $movie = DB::table('movies')->where('id', $movieId)->first();
        if (!$movie) return redirect()->route('movies.index')->withErrors('Không tìm thấy phim.');

        $date = $r->query('date'); // yyyy-mm-dd
        $day  = $date ? Carbon::parse($date) : Carbon::today();

        // Load suất chiếu trong ngày, kèm phòng
        $showtimes = DB::table('showtimes as st')
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->where('st.movie_id', $movieId)
            ->whereDate('st.start_time', $day->toDateString())
            ->orderBy('st.start_time')
            ->select('st.id', 'st.start_time', 'rm.name as room_name', 'rm.id as room_id')
            ->get();

        return view('movies.show', compact('movie', 'showtimes', 'day'));
    }
}
