<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');   // booked / used / canceled
        $q      = $request->input('q');        // search user/movie/email
        $date   = $request->input('date');     // filter theo ngày suất chiếu

        // ----- XÁC ĐỊNH NHÃN GHẾ THEO SCHEMA THỰC TẾ -----
        // Ưu tiên: code -> label -> (row_letter + seat_number) -> fallback theo id
        if (Schema::hasColumn('seats', 'code')) {
            $seatLabelExpr = "s.code";
        } elseif (Schema::hasColumn('seats', 'label')) {
            $seatLabelExpr = "s.label";
        } elseif (Schema::hasColumn('seats', 'row_letter') && Schema::hasColumn('seats', 'seat_number')) {
            $seatLabelExpr = "CONCAT(s.row_letter, '', s.seat_number)";
        } else {
            // Không có cột nào quen thuộc -> đặt nhãn tạm theo id
            $seatLabelExpr = "CONCAT('Seat#', s.id)";
        }

        // ----- XÁC ĐỊNH KHÓA NGOẠI MOVIE Ở BẢNG SHOWTIMES -----
        $movieFk = Schema::hasColumn('showtimes','movie_id') ? 'movie_id'
                 : (Schema::hasColumn('showtimes','film_id') ? 'film_id' : null);

        if (!$movieFk) {
            // Không có movie_id/film_id -> tránh crash, trả trang rỗng kèm cảnh báo.
            $tickets = collect();
            return response('admin.tickets.index (schema showtimes thiếu movie_id/film_id) 0 rows');
        }

        // ----- QUERY -----
        $query = DB::table('tickets as t')
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->join('showtimes as st', 'st.id', '=', 't.showtime_id')
            ->join('movies as mv', "mv.id", '=', "st.$movieFk")
            ->join('rooms as rm', 'rm.id', '=', 'st.room_id')
            ->leftJoin('seats as s', 's.id', '=', 't.seat_id')
            ->selectRaw("
                t.id,
                t.qr_code,
                t.status,
                t.final_price,
                t.discount_amount,
                t.membership_discount_rate,
                t.created_at,
                u.name as user_name,
                u.email as user_email,
                mv.title as movie_title,
                rm.name as room_name,
                st.start_time,
                {$seatLabelExpr} as seat_label
            ")
            ->orderByDesc('t.created_at');

        if ($status) {
            $query->where('t.status', $status);
        }
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('u.name','like',"%$q%")
                    ->orWhere('u.email','like',"%$q%")
                    ->orWhere('mv.title','like',"%$q%");
            });
        }
        if ($date) {
            $query->whereDate('st.start_time', $date);
        }

        $tickets = $query->paginate(15)->withQueryString();

        // Nếu CHƯA có view, bạn có thể tạm trả demo để test:
        // return response('admin.tickets.index (demo) '.$tickets->total().' rows');

        return view('admin.tickets.index', compact('tickets','status','q','date'));
    }

    public function cancel($ticketId)
    {
        // Chính sách: chỉ cho huỷ nếu chưa used
        $ticket = DB::table('tickets')->where('id', $ticketId)->first();
        if (!$ticket) return back()->withErrors('Không tìm thấy vé.');
        if ($ticket->status === 'used') return back()->withErrors('Vé đã sử dụng, không thể huỷ.');

        DB::table('tickets')->where('id',$ticketId)->update([
            'status' => 'canceled',
            'updated_at' => now(),
        ]);

        return back()->with('ok','Đã huỷ vé.');
    }

    public function refund($ticketId)
    {
        // Tuỳ chính sách: ví dụ chỉ hoàn khi đang 'canceled'
        $ticket = DB::table('tickets')->where('id', $ticketId)->first();
        if (!$ticket) return back()->withErrors('Không tìm thấy vé.');
        if ($ticket->status !== 'canceled') return back()->withErrors('Chỉ hoàn tiền vé đã huỷ.');

        // Demo: ghi log hoàn, thực tế gọi cổng thanh toán… rồi cập nhật trạng thái
        // Ở đây ta giữ status = canceled và hiển thị flash message
        return back()->with('ok', 'Đã đánh dấu hoàn tiền (demo).');
    }
}
