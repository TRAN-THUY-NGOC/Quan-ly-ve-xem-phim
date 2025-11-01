<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /** GET /showtimes/{showtime}/tickets/create */
    public function create(Showtime $showtime)
    {
        // Movie + Room
        $movie = \DB::table('movies')->where('id', $showtime->movie_id)->first();
        $room  = \DB::table('rooms')->where('id',  $showtime->room_id)->first();

        // Ghế + loại ghế
        $seats = \DB::table('seats as s')
            ->leftJoin('seat_types as t','t.id','=','s.seat_type_id')
            ->where('s.room_id', $showtime->room_id)
            ->orderBy('s.row_letter')->orderBy('s.seat_number')
            ->select('s.id','s.row_letter','s.seat_number','s.code','s.seat_type_id','t.name as seat_type')
            ->get();

        // Ghế đã đặt
        $occupied = \DB::table('tickets')
            ->where('showtime_id', $showtime->id)
            ->where('status','!=','canceled')
            ->pluck('seat_id')->toArray();

        // Giá hiệu lực cho suất (final_price > price+base)
        $sp = \DB::table('showtime_prices')
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
            $effective = \DB::table('seat_types')
                ->pluck('base_price','id')->map(fn($v)=>(int)$v)->toArray();
        }
        $priceMap = $effective;

        // Các suất khác để đổi
        $otherShowtimes = \DB::table('showtimes as st')
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->where('st.movie_id', $showtime->movie_id)
            ->whereDate('st.start_time', '>=', \Illuminate\Support\Carbon::today()->toDateString())
            ->orderBy('st.start_time')
            ->select('st.id','st.start_time','rm.name as room_name')
            ->get();

        // Lấy loại vé từ DB (Admin cấu hình)
        $types = TicketType::activeOrdered();
        $ticketTypes = $types->mapWithKeys(fn($t)=>[
            $t->key => ['label'=>$t->label, 'coef'=>$t->coef]
        ])->toArray();
        $defaultType = $types->first()->key ?? 'adult';

        return view('tickets.create', compact(
            'showtime','movie','room','seats','occupied',
            'priceMap','otherShowtimes','ticketTypes','defaultType'
        ));
    }

    /** POST /showtimes/{showtime}/tickets */
    public function store(Showtime $showtime, Request $request)
    {
        // Loại vé hợp lệ (theo DB)
        $keys = TicketType::where('is_active',true)->pluck('key')->toArray();

        $data = $request->validate([
            'seat_ids'    => ['required','array','min:1','max:10'],
            'seat_ids.*'  => ['integer'],
            'ticket_type' => ['required', 'in:'.implode(',', $keys)],
            'pay_now'     => ['nullable','boolean'],
        ]);

        $userId = Auth::id();
        $status = $request->boolean('pay_now') ? 'paid' : 'reserved';

        // Giá hiệu lực
        $sp = \DB::table('showtime_prices')
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
            $effective = \DB::table('seat_types')
                ->pluck('base_price','id')->map(fn($v)=>(int)$v)->toArray();
        }

        // Hệ số theo DB
        $coefMap = TicketType::whereIn('key',$keys)->get()
            ->mapWithKeys(fn($t)=>[$t->key => (float)$t->coef])->toArray();
        $coef = $coefMap[$data['ticket_type']] ?? 1.0;

        \DB::transaction(function () use ($data, $showtime, $userId, $effective, $coef, $status) {
            // khoá ghế
            $exists = \DB::table('tickets')
                ->where('showtime_id',$showtime->id)
                ->whereIn('seat_id',$data['seat_ids'])
                ->where('status','!=','canceled')
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                abort(409, 'Một hoặc nhiều ghế đã bị người khác giữ chỗ. Vui lòng chọn lại.');
            }

            $rows = \DB::table('seats')
                ->whereIn('id',$data['seat_ids'])
                ->select('id','seat_type_id')->get();

            foreach ($rows as $row) {
                $basePrice  = (int)($effective[$row->seat_type_id] ?? 0);
                $finalPrice = (int) round($basePrice * $coef);

                \DB::table('tickets')->insert([
                    'showtime_id' => $showtime->id,
                    'user_id'     => $userId,
                    'seat_id'     => $row->id,
                    'ticket_type' => $data['ticket_type'],
                    'status'      => $status, // 'paid' nếu pay_now=1
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
