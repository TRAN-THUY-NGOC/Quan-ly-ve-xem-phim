<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketsController extends Controller
{
    public function index()
    {
        // join để xem tổng thể
        $tickets = DB::table('tickets')
            ->join('users','tickets.user_id','=','users.id')
            ->join('showtimes','tickets.showtime_id','=','showtimes.id')
            ->join('movies','showtimes.movie_id','=','movies.id')
            ->join('rooms','showtimes.room_id','=','rooms.id')
            ->leftJoin('payments','tickets.id','=','payments.ticket_id')
            ->select(
                'tickets.*',
                'users.name as user_name',
                'movies.title as movie_title',
                'rooms.name as room_name',
                'showtimes.start_time',
                'payments.status as payment_status',
                'payments.amount as paid_amount'
            )
            ->orderByDesc('tickets.created_at')
            ->paginate(20);

        // return view('admin.tickets.index', compact('tickets'));
        return response('admin.tickets.index (demo) '.$tickets->count().' rows');
    }

    public function cancel($ticketId)
    {
        $ticket = DB::table('tickets')->where('id',$ticketId)->first();
        if (!$ticket) return back()->withErrors('Không tìm thấy vé.');
        if ($ticket->status !== 'booked') return back()->withErrors('Chỉ hủy vé đang ở trạng thái booked.');

        DB::table('tickets')->where('id',$ticketId)->update(['status'=>'canceled']);
        return back()->with('ok','Đã hủy vé #'.$ticketId);
    }

    public function refund($ticketId)
    {
        $ticket = DB::table('tickets')->where('id',$ticketId)->first();
        if (!$ticket) return back()->withErrors('Không tìm thấy vé.');

        DB::transaction(function () use ($ticketId) {
            DB::table('payments')->where('ticket_id',$ticketId)->update(['status'=>'failed']);
            DB::table('tickets')->where('id',$ticketId)->update(['status'=>'canceled']);
        });

        return back()->with('ok','Đã hoàn/hủy vé #'.$ticketId);
    }
}
