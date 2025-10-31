<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show($ticketId)
    {
        $t = DB::table('tickets as t')
            ->join('showtimes as st','st.id','=','t.showtime_id')
            ->join('movies as mv','mv.id','=','st.movie_id')
            ->join('rooms as rm','rm.id','=','st.room_id')
            ->leftJoin('seats as s','s.id','=','t.seat_id')
            ->select('t.*','mv.title as movie_title','rm.name as room_name','st.start_time',
                DB::raw("COALESCE(s.code, s.label, CONCAT(s.row_letter,'',s.seat_number), CAST(s.id as char)) as seat_label"))
            ->where('t.id',$ticketId)
            ->first();

        if (!$t) return redirect()->route('tickets.history')->withErrors('Không tìm thấy vé.');

        return view('payments.show', compact('t'));
    }

    /**
     * Demo “thanh toán”: đổi status -> paid
     */
    public function complete(Request $r, $ticketId)
    {
        $ok = DB::table('tickets')->where('id',$ticketId)
            ->update(['status'=>'paid','updated_at'=>now()]);
        if (!$ok) return back()->withErrors('Thanh toán thất bại.');

        // TODO: gửi email vé PDF/QR nếu muốn
        return redirect()->route('tickets.history')->with('ok','Thanh toán thành công. Vé đã được kích hoạt.');
    }
}
