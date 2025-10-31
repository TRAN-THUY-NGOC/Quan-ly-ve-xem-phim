<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricesController extends Controller
{
    public function index(Request $r)
    {
        // Bộ lọc (tuỳ chọn)
        $movieId = $r->integer('movie_id');
        $roomId  = $r->integer('room_id');
        $date    = $r->date('date');

        $q = DB::table('showtime_prices as sp')
            ->join('showtimes as st', 'st.id', '=', 'sp.showtime_id')
            ->join('seat_types as stp', 'stp.id', '=', 'sp.seat_type_id')
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->join('movies as mv', 'mv.id', '=', 'st.movie_id')
            ->select(
                'sp.id',
                'sp.showtime_id',
                'sp.seat_type_id',
                'sp.price_modifier',
                'st.start_time',
                'st.end_time',
                'rm.name as room_name',
                'mv.title as movie_title',
                'stp.name as seat_type_name',
                'stp.base_price'
            )
            ->orderBy('st.start_time','desc');

        if ($movieId) $q->where('st.movie_id', $movieId);
        if ($roomId)  $q->where('st.room_id',  $roomId);
        if ($date)    $q->whereDate('st.start_time', $date);

        $prices = $q->paginate(15)->withQueryString();

        // Dữ liệu filter
        $movies = DB::table('movies')->orderBy('title')->get(['id','title']);
        $rooms  = DB::table('rooms')->orderBy('name')->get(['id','name']);

        return view('admin.prices.index', compact('prices','movies','rooms','movieId','roomId','date'));
    }

    // Cập nhật hàng loạt price_modifier
    public function store(Request $r)
    {
        $r->validate([
            'price' => ['required','array'],
            'price.*.id' => ['required','integer','exists:showtime_prices,id'],
            'price.*.price_modifier' => ['required','numeric','min:-1000000','max:10000000'],
        ]);

        foreach ($r->input('price') as $row) {
            DB::table('showtime_prices')
              ->where('id', $row['id'])
              ->update(['price_modifier' => $row['price_modifier']]);
        }

        return back()->with('ok', 'Đã cập nhật giá.');
    }
}
