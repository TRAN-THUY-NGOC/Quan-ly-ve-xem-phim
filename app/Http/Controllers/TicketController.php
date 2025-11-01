<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon as Carbon;

class TicketController extends Controller
{
    /**
     * GET /showtimes/{showtime}/tickets/create
     */
    public function create(Showtime $showtime)
    {
        // Thông tin phim & phòng
        $movie = DB::table('movies')->where('id', $showtime->movie_id)->first();
        $room  = DB::table('rooms')->where('id',  $showtime->room_id)->first();

        // Ghế trong phòng + loại ghế
        $seats = DB::table('seats as s')
            ->leftJoin('seat_types as t','t.id','=','s.seat_type_id')
            ->where('s.room_id', $showtime->room_id)
            ->orderBy('s.row_letter')->orderBy('s.seat_number')
            ->select(
                's.id','s.row_letter','s.seat_number','s.code',
                's.seat_type_id',
                't.name as seat_type'
            )
            ->get();

        // Ghế đã đặt cho suất này (không tính vé đã hủy)
        $occupied = DB::table('tickets')
            ->where('showtime_id', $showtime->id)
            ->where('status','!=','canceled')
            ->pluck('seat_id')->toArray();

        // Giá gốc theo suất + loại ghế (nếu chưa cấu hình thì rơi về base_price của seat_types)
        $prices = DB::table('showtime_prices as p')
            ->where('p.showtime_id',$showtime->id)
            ->pluck('price','seat_type_id');  // [seat_type_id => price]

        $basePrices = DB::table('seat_types')->pluck('base_price','id');

        // Chuẩn hoá map giá dùng để hiển thị và gắn vào từng ghế
        $priceMap = [];
        foreach ($basePrices as $typeId => $base) {
            $priceMap[$typeId] = $prices[$typeId] ?? $base ?? 0;
        }

        // Danh sách các suất khác của cùng phim (để user có thể đổi nhanh)
        $otherShowtimes = DB::table('showtimes as st')
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->where('st.movie_id', $showtime->movie_id)
            ->whereDate('st.start_time', '>=', Carbon::today()->toDateString())
            ->orderBy('st.start_time')
            ->select('st.id', 'st.start_time', 'rm.name as room_name')
            ->get();

        // Các loại vé (hệ số giá) – có thể chuyển thành bảng DB nếu muốn
        $ticketTypes = [
            'adult'   => ['label' => 'Người lớn', 'coef' => 1.00],
            'student' => ['label' => 'HSSV',     'coef' => 0.80],
            'child'   => ['label' => 'Trẻ em',   'coef' => 0.60],
        ];
        $defaultType = 'adult';

        return view('tickets.create', [
            'showtime'       => $showtime,
            'movie'          => $movie,
            'room'           => $room,
            'seats'          => $seats,
            'occupied'       => $occupied,
            'priceMap'       => $priceMap,
            'otherShowtimes' => $otherShowtimes,
            'ticketTypes'    => $ticketTypes,
            'defaultType'    => $defaultType,
        ]);
    }

    /**
     * POST /showtimes/{showtime}/tickets
     */
    public function store(Showtime $showtime, Request $request)
    {
        $data = $request->validate([
            'seat_ids'      => ['required','array','min:1','max:10'],
            'seat_ids.*'    => ['integer'],
            'ticket_type'   => ['required','in:adult,student,child'],
        ]);

        $userId = Auth::id();

        // Giá gốc theo seat_type
        $prices = DB::table('showtime_prices')
            ->where('showtime_id',$showtime->id)
            ->pluck('price','seat_type_id');
        $base   = DB::table('seat_types')->pluck('base_price','id');

        // Hệ số loại vé
        $coefMap = ['adult'=>1.00,'student'=>0.80,'child'=>0.60];
        $coef    = $coefMap[$data['ticket_type']] ?? 1.0;

        DB::transaction(function () use ($data, $showtime, $userId, $prices, $base, $coef) {
            // Khoá ghế đang chọn để tránh bị tranh vé
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
            $rows = DB::table('seats')
                ->whereIn('id',$data['seat_ids'])
                ->select('id','seat_type_id')
                ->get();

            foreach ($rows as $row) {
                $typeId     = $row->seat_type_id;
                $basePrice  = $prices[$typeId] ?? ($base[$typeId] ?? 0);
                $finalPrice = (int) round($basePrice * $coef);

                DB::table('tickets')->insert([
                    'showtime_id' => $showtime->id,
                    'user_id'     => $userId,
                    'seat_id'     => $row->id,
                    'ticket_type' => $data['ticket_type'], // thêm cột này nếu DB có
                    'status'      => 'reserved',
                    'price'       => $basePrice,
                    'final_price' => $finalPrice,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        });

        return redirect()->route('tickets.history')
            ->with('ok','Đặt vé thành công! Bạn có thể thanh toán trong mục “Vé của tôi”.');
    }
}
