<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        // Doanh thu theo ngày
        $revenueDaily = DB::table('payments')
            ->selectRaw('DATE(paid_at) as d, SUM(amount) as total')
            ->where('status','completed')
            ->groupBy('d')->orderByDesc('d')->limit(30)->get();

        // Lấp đầy phòng theo suất
        $occupancy = DB::table('tickets')
            ->join('showtimes','tickets.showtime_id','=','showtimes.id')
            ->join('rooms','showtimes.room_id','=','rooms.id')
            ->selectRaw('showtimes.id as sid, COUNT(tickets.id) as sold, rooms.capacity, (COUNT(tickets.id)/rooms.capacity) as ratio, showtimes.start_time')
            ->groupBy('sid','rooms.capacity','showtimes.start_time')
            ->orderByDesc('start_time')->limit(30)->get();

        // Top phim bán chạy
        $topMovies = DB::table('tickets')
            ->join('showtimes','tickets.showtime_id','=','showtimes.id')
            ->join('movies','showtimes.movie_id','=','movies.id')
            ->selectRaw('movies.title, COUNT(tickets.id) as sold')
            ->groupBy('movies.title')->orderByDesc('sold')->limit(10)->get();

        // return view('admin.reports.index', compact('revenueDaily','occupancy','topMovies'));
        return response('admin.reports.index (demo): rev='.$revenueDaily->count().', occ='.$occupancy->count().', top='.$topMovies->count());
    }
}
