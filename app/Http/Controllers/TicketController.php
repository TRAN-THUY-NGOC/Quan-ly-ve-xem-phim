<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Showtime;

class TicketController extends Controller
{
    /** Trang chọn ghế và loại vé */
 public function create(Showtime $showtime)
    {
        // === Lấy thông tin phim và phòng chiếu ===
        $movie = DB::table('movies')->where('id', $showtime->movie_id)->first();
        $room  = DB::table('rooms')->where('id', $showtime->room_id)->first();

        // === Lấy danh sách ghế theo phòng ===
        $seats = DB::table('seats as s')
            ->leftJoin('seat_types as t', 't.id', '=', 's.seat_type_id')
            ->where('s.room_id', $showtime->room_id)
            ->select('s.id', 's.row_letter', 's.seat_number', 's.code', 's.seat_type_id', 't.name as seat_type')
            ->orderBy('s.row_letter')
            ->orderBy('s.seat_number')
            ->get();

        // === Ghế đã đặt ===
        $occupied = DB::table('tickets')
            ->where('showtime_id', $showtime->id)
            ->where('status', '!=', 'canceled')
            ->pluck('seat_id')
            ->toArray();

        // === Lấy giá ghế từ showtime_prices hoặc seat_types ===
        $rows = DB::table('showtime_prices')
            ->where('showtime_id', $showtime->id)
            ->select('seat_type_id', 'base_price', 'price', 'final_price')
            ->get();

        $effective = [];
        foreach ($rows as $r) {
            if (!is_null($r->final_price)) {
                $effective[$r->seat_type_id] = (int)$r->final_price;
            } else {
                $effective[$r->seat_type_id] = (int)($r->base_price ?? 0) + (int)($r->price ?? 0);
            }
        }

        // Nếu chưa có giá riêng -> lấy base_price trong seat_types
        if (empty($effective)) {
            $effective = DB::table('seat_types')
                ->pluck('base_price', 'id')
                ->map(fn($v) => (int)$v)
                ->toArray();
        }

        $priceMap = $effective;

        // === Lấy loại ghế từ bảng seat_types (thay cho “Người lớn/HSSV/Trẻ em”) ===
        $ticketTypes = DB::table('seat_types')
            ->select('id', 'name', 'base_price')
            ->orderBy('id')
            ->get();

        // === Suất chiếu khác cùng phim (tùy chọn hiển thị) ===
        $otherShowtimes = DB::table('showtimes as st')
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->where('st.movie_id', $showtime->movie_id)
            ->whereDate('st.start_time', '>=', now()->toDateString())
            ->select('st.id', 'st.start_time', 'rm.name as room_name')
            ->orderBy('st.start_time')
            ->get();

        return view('tickets.create', compact(
            'showtime',
            'movie',
            'room',
            'seats',
            'occupied',
            'priceMap',
            'otherShowtimes',
            'ticketTypes'
        ));
    }
    /** Xử lý lưu vé */
    public function store(Showtime $showtime, Request $request)
    {
        // Các loại vé hợp lệ cố định
        $keys = ['adult', 'student', 'child'];

        $data = $request->validate([
            'seat_ids'    => ['required','array','min:1','max:10'],
            'seat_ids.*'  => ['integer'],
            'ticket_type' => ['required', 'in:'.implode(',', $keys)],
            'pay_now'     => ['nullable','boolean'],
        ]);

        $userId = Auth::id();
        $status = $request->boolean('pay_now') ? 'paid' : 'reserved';

        // Lấy giá hiệu lực cho loại ghế
        $sp = DB::table('showtime_prices')
            ->where('showtime_id', $showtime->id)
            ->select('seat_type_id','base_price','price','final_price')
            ->get();

        $effective = [];
        foreach ($sp as $r) {
            $effective[$r->seat_type_id] = !is_null($r->final_price)
                ? (int)$r->final_price
                : (int)($r->base_price ?? 0) + (int)($r->price ?? 0);
        }

        if (empty($effective)) {
            $effective = DB::table('seat_types')
                ->pluck('base_price','id')
                ->map(fn($v)=>(int)$v)
                ->toArray();
        }

        // Hệ số loại vé cố định
        $coefMap = ['adult'=>1.0, 'student'=>0.8, 'child'=>0.6];
        $coef = $coefMap[$data['ticket_type']] ?? 1.0;

        DB::transaction(function () use ($data, $showtime, $userId, $effective, $coef, $status) {
            // Kiểm tra ghế đã bị người khác giữ chưa
            $exists = DB::table('tickets')
                ->where('showtime_id',$showtime->id)
                ->whereIn('seat_id',$data['seat_ids'])
                ->where('status','!=','canceled')
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                abort(409, 'Một hoặc nhiều ghế đã bị người khác giữ chỗ. Vui lòng chọn lại.');
            }

            // Tạo vé mới
            $rows = DB::table('seats')
                ->whereIn('id',$data['seat_ids'])
                ->select('id','seat_type_id')
                ->get();

            foreach ($rows as $row) {
                $basePrice  = (int)($effective[$row->seat_type_id] ?? 0);
                $finalPrice = (int) round($basePrice * $coef);

                DB::table('tickets')->insert([
                    'showtime_id' => $showtime->id,
                    'user_id'     => $userId,
                    'seat_id'     => $row->id,
                    'ticket_type' => $data['ticket_type'],
                    'status'      => $status,
                    'price'       => $basePrice,
                    'final_price' => $finalPrice,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        });

        return redirect()->route('tickets.history')
            ->with('ok', $status === 'paid'
                ? 'Thanh toán thành công!'
                : 'Đặt vé thành công! Bạn có thể thanh toán trong mục “Vé của tôi”.');
    }
}
