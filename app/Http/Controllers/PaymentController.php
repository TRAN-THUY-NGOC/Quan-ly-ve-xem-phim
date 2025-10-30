<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show($ticketId)
    {
        $ticket = Ticket::with(['showtime.movie','seat','user'])->findOrFail($ticketId);
        $payment = $ticket->payment; // quan hệ 1-1

        return view('payments.show', compact('ticket','payment'));
    }

    // callback giả lập thanh toán thành công
    public function complete(Request $request, $ticketId)
    {
        DB::transaction(function () use ($ticketId) {
            $ticket = Ticket::lockForUpdate()->findOrFail($ticketId);
            $payment = $ticket->payment()->lockForUpdate()->first();

            $payment->update([
                'status' => 'completed',
                'paid_at'=> now(),
            ]);

            $ticket->update(['status' => 'used']); // hoặc 'booked' → tuỳ nghiệp vụ
        });

        return redirect()->route('tickets.detail', $ticketId)->with('success','Thanh toán thành công.');
    }
}