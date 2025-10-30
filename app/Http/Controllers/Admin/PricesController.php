<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Showtime;
use App\Models\SeatType;

class PricesController extends Controller
{
    public function index()
    {
        $rows = DB::table('showtime_prices')
            ->join('showtimes','showtime_prices.showtime_id','=','showtimes.id')
            ->join('seat_types','showtime_prices.seat_type_id','=','seat_types.id')
            ->join('movies','showtimes.movie_id','=','movies.id')
            ->select('showtime_prices.*','movies.title','seat_types.name as seat_type_name','showtimes.start_time')
            ->orderByDesc('showtimes.start_time')->paginate(20);

        $showtimes = Showtime::with('movie')->orderByDesc('start_time')->limit(50)->get();
        $seatTypes = SeatType::orderBy('name')->get();

        // return view('admin.prices.index', compact('rows','showtimes','seatTypes'));
        return response('admin.prices.index (demo) '.$rows->count().' rows');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'showtime_id'   => 'required|exists:showtimes,id',
            'seat_type_id'  => 'required|exists:seat_types,id',
            'price_modifier'=> 'required|numeric|min:0|max:10000000',
        ]);

        DB::table('showtime_prices')->updateOrInsert(
            ['showtime_id'=>$data['showtime_id'], 'seat_type_id'=>$data['seat_type_id']],
            ['price_modifier'=>$data['price_modifier']]
        );

        return back()->with('ok','Đã lưu giá suất chiếu.');
    }
}
