<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Voucher;
use App\Models\Payment;
use Illuminate\Database\QueryException;

class TicketController extends Controller
{
    // form chọn ghế theo suất chiếu
    public function create($showtimeId)
    {
        $showtime = Showtime::with(['movie','room'])->findOrFail($showtimeId);
        $seats    = Seat::where('room_id', $showtime->room_id)->orderBy('row_label')->orderBy('seat_number')->get();

        // các ghế đã bán cho suất này
        $soldSeatIds = Ticket::where('showtime_id', $showtimeId)->pluck('seat_id')->all();

        return view('tickets.create', compact('showtime','seats','soldSeatIds'));
    }

    // đặt 1 ghế (có thể mở rộng nhiều ghế bằng mảng)
    public function store(Request $request, $showtimeId)
    {
        $request->validate([
            'seat_id'   => ['required','integer','exists:seats,id'],
            'voucher'   => ['nullable','string','max:50'],
            'pay_method'=> ['required','in:COD,ATM,MoMo,ZaloPay'],
        ]);

        $userId = auth()->id();
        $seatId = (int) $request->seat_id;

        // Tính giá: base_price (seat_types) + modifier (showtime_prices)
        $showtime = Showtime::with(['movie','room'])->findOrFail($showtimeId);
        $seat     = Seat::with('type')->findOrFail($seatId);

        // lấy modifier theo loại ghế cho suất
        $modifier = DB::table('showtime_prices')
            ->where('showtime_id', $showtimeId)
            ->where('seat_type_id', $seat->seat_type_id)
            ->value('price_modifier') ?? 0;

        $base     = $seat->type->base_price;               // DECIMAL(10,2)
        $price    = $base + $modifier;                     // giá niêm yết

        // áp voucher (nếu có)
        $discount = 0;
        if ($code = $request->voucher) {
            $voucher = Voucher::where('code',$code)
                ->where('is_active',1)
                ->where('start_date','<=',now())
                ->where('end_date','>=',now())
                ->first();

            if ($voucher) {
                if ($voucher->type === 'fixed') {
                    $discount = min($price, $voucher->value);
                } else { // percentage
                    $discount = $price * floatval($voucher->value);
                    if ($voucher->max_discount_amount) {
                        $discount = min($discount, $voucher->max_discount_amount);
                    }
                }
            }
        }

        $final = max(0, $price - $discount);

        try {
            $ticketId = DB::transaction(function () use ($userId,$showtimeId,$seatId,$final,$discount,$request) {
                // Insert ticket (đụng UNIQUE sẽ nổ ở đây)
                $ticket = Ticket::create([
                    'user_id'                   => $userId,
                    'voucher_id'                => null, // nếu áp voucher thật thì lưu id
                    'showtime_id'               => $showtimeId,
                    'seat_id'                   => $seatId,
                    'qr_code'                   => uniqid('QR', true),
                    'final_price'               => $final,
                    'discount_amount'           => $discount,
                    'membership_discount_rate'  => 0, // nếu có membership thì set tỉ lệ
                    'status'                    => 'booked',
                    'points_earned'             => 0,
                ]);

                // Tạo payment (pending)
                $payment = Payment::create([
                    'ticket_id'       => $ticket->id,
                    'amount'          => $final,
                    'payment_method'  => $request->pay_method,
                    'status'          => 'pending',
                ]);

                return $ticket->id;
            }, 3);

        } catch (QueryException $e) {
            // 1062 = duplicate entry → ghế đã có người đặt
            if ($e->errorInfo[1] == 1062) {
                return back()->withErrors('Ghế này vừa được người khác đặt. Vui lòng chọn ghế khác.');
            }
            throw $e;
        }

        return redirect()->route('payments.show', ['ticket' => $ticketId]);
    }
}
