<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Showtime;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // GET /showtimes/{showtime}/tickets/create
    public function create(Showtime $showtime)
    {
        // Movie + Room
        $movie = DB::table('movies')->where('id', $showtime->movie_id)->first();
        $room  = DB::table('rooms')->where('id',  $showtime->room_id)->first();

        // Ghế trong phòng
        $seats = DB::table('seats as s')
            ->leftJoin('seat_types as t','t.id','=','s.seat_type_id')
            ->where('s.room_id', $showtime->room_id)
            ->orderBy('s.row_letter')->orderBy('s.seat_number')
            ->select('s.id','s.row_letter','s.seat_number','s.code','s.seat_type_id','t.name as seat_type','t.color as seat_color')
            ->get();

        // Ghế đã được đặt cho suất này (không tính vé đã hủy)
        $occupied = DB::table('tickets')
            ->where('showtime_id', $showtime->id)
            ->where('status','!=','canceled')
            ->pluck('seat_id')->toArray();

        // Giá theo suất + loại ghế (nếu không có thì rơi về base_price trong seat_types)
        $prices = DB::table('showtime_prices as p')
            ->where('p.showtime_id',$showtime->id)
            ->pluck('price','seat_type_id'); // [seat_type_id => price]

        $basePrices = DB::table('seat_types')->pluck('base_price','id'); // fallback

        // Chuẩn hóa map giá
        $priceMap = [];
        foreach ($basePrices as $typeId => $base) {
            $priceMap[$typeId] = $prices[$typeId] ?? $base ?? 0;
        }

        return view('tickets.create', [
            'showtime' => $showtime,
            'movie'    => $movie,
            'room'     => $room,
            'seats'    => $seats,
            'occupied' => $occupied,
            'priceMap' => $priceMap,
        ]);
    }

    // POST /showtimes/{showtime}/tickets
    public function store(Showtime $showtime, Request $r)
    {
        $data = $r->validate([
            'seat_ids'   => ['required','array','min:1','max:10'],
            'seat_ids.*' => ['integer'],
        ]);

        $userId = Auth::id();

        // Map giá cho từng seat_type
        $prices = DB::table('showtime_prices')->where('showtime_id',$showtime->id)
            ->pluck('price','seat_type_id');
        $base   = DB::table('seat_types')->pluck('base_price','id');

        DB::transaction(function () use ($data, $showtime, $userId, $prices, $base) {

            // Khóa các seat đang mua, đảm bảo không “cướp vé”
            $exists = DB::table('tickets')
                ->where('showtime_id',$showtime->id)
                ->whereIn('seat_id',$data['seat_ids'])
                ->where('status','!=','canceled')
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                abort(409, 'Một hoặc nhiều ghế đã bị người khác giữ chỗ. Vui lòng chọn lại.');
            }

            // Lấy thông tin ghế để tính giá
            $rows = DB::table('seats')->whereIn('id',$data['seat_ids'])
                ->select('id','seat_type_id')->get();

            foreach ($rows as $row) {
                $typeId = $row->seat_type_id;
                $price  = $prices[$typeId] ?? ($base[$typeId] ?? 0);

                DB::table('tickets')->insert([
                    'showtime_id' => $showtime->id,
                    'user_id'     => $userId,
                    'seat_id'     => $row->id,
                    'status'      => 'reserved',    // reserved -> paid sau khi thanh toán
                    'price'       => $price,
                    'final_price' => $price,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        });

        return redirect()->route('tickets.history')
            ->with('ok','Đặt vé thành công! Bạn có thể thanh toán trong mục Vé của tôi.');
    }
}
