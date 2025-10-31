<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    /**
     * Form chọn ghế cho 1 suất chiếu
     */
    public function create($showtimeId)
    {
        $st = DB::table('showtimes as st')
            ->join('movies as mv','mv.id','=','st.movie_id')
            ->join('rooms as rm','rm.id','=','st.room_id')
            ->select('st.*','mv.title as movie_title','rm.name as room_name','rm.id as room_id')
            ->where('st.id',$showtimeId)->first();

        if (!$st) return back()->withErrors('Không tìm thấy suất chiếu.');

        // Lấy ghế của phòng
        $seatLabelExpr = $this->seatLabelSql(); // COALESCE theo schema
        $seats = DB::table('seats')
            ->where('room_id', $st->room_id)
            ->select('id', DB::raw("$seatLabelExpr as label"), 'row_letter','seat_number','code','label as label_raw')
            ->orderByRaw("COALESCE(row_letter,'Z') asc, seat_number asc")
            ->get();

        // Ghế đã được giữ/đặt cho suất này (trạng thái hợp lệ)
        $taken = DB::table('tickets')
            ->where('showtime_id', $showtimeId)
            ->whereIn('status',['pending','paid','used'])
            ->pluck('seat_id')->filter()->all();

        // Giá gốc theo loại ghế? (tuỳ bảng price). Ở bản demo, mình lấy giá gốc 80k.
        $basePrice = 80000;

        return view('tickets.create', [
            'st'        => $st,
            'seats'     => $seats,
            'taken'     => $taken,
            'basePrice' => $basePrice,
        ]);
    }

    /**
     * Tạo vé (đặt chỗ). Demo: set status = pending, tạo mã QR/code.
     */
    public function store(Request $r, $showtimeId)
    {
        $data = $r->validate([
            'seat_id' => ['required','integer'],
        ]);

        $user = Auth::user();

        // Check suất tồn tại
        $st = DB::table('showtimes')->where('id',$showtimeId)->first();
        if (!$st) return back()->withErrors('Suất chiếu không tồn tại.');

        // Check seat thuộc đúng room của suất và chưa có vé khác giữ
        $seatRoom = DB::table('seats')->where('id',$data['seat_id'])->value('room_id');
        if (!$seatRoom) return back()->withErrors('Ghế không hợp lệ.');
        if ((int)$seatRoom !== (int)$st->room_id) return back()->withErrors('Ghế không thuộc phòng của suất này.');

        $exists = DB::table('tickets')
            ->where('showtime_id',$showtimeId)
            ->where('seat_id',$data['seat_id'])
            ->whereIn('status',['pending','paid','used'])
            ->exists();
        if ($exists) return back()->withErrors('Ghế đã có người giữ/đặt.');

        // Tính giá (demo)
        $basePrice = 80000;
        $discount  = 0;
        $memberRate= 0;
        $final     = $basePrice - $discount;

        $ticketId = DB::table('tickets')->insertGetId([
            'user_id'                  => $user->id,
            'showtime_id'              => $showtimeId,
            'seat_id'                  => $data['seat_id'],
            'qr_code'                  => Str::upper(Str::random(10)),
            'status'                   => 'pending', // chờ thanh toán
            'discount_amount'          => $discount,
            'membership_discount_rate' => $memberRate,
            'final_price'              => $final,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        return redirect()->route('payments.show', $ticketId)
            ->with('ok','Đã giữ ghế, vui lòng thanh toán.');
    }

    /**
     * Lịch sử vé của user
     */
    public function history()
    {
        $userId = auth()->id();

        $seatLabelExpr = $this->seatLabelSql('s');

        $tickets = DB::table('tickets as t')
            ->join('showtimes as st','st.id','=','t.showtime_id')
            ->join('movies as mv','mv.id','=','st.movie_id')
            ->join('rooms as rm','rm.id','=','st.room_id')
            ->leftJoin('seats as s','s.id','=','t.seat_id')
            ->where('t.user_id',$userId)
            ->orderByDesc('t.created_at')
            ->select('t.*','mv.title as movie_title','rm.name as room_name','st.start_time',
                DB::raw("$seatLabelExpr as seat_label"))
            ->paginate(15);

        return view('tickets.history', compact('tickets'));
    }

    private function seatLabelSql(?string $alias = null): string
    {
        $tbl = $alias ?: 'seats';
        $parts = [];
        if (\Schema::hasColumn('seats','code'))       $parts[] = "$tbl.code";
        if (\Schema::hasColumn('seats','label'))      $parts[] = "$tbl.label";
        if (\Schema::hasColumn('seats','row_letter') && \Schema::hasColumn('seats','seat_number')) {
            $parts[] = "CONCAT($tbl.row_letter,'', $tbl.seat_number)";
        }
        $parts[] = "CAST($tbl.id AS CHAR)";
        return 'COALESCE('.implode(', ', $parts).')';
    }
}
