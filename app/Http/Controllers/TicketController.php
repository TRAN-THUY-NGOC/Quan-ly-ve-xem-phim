<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Showtime;
use App\Models\Voucher;

class TicketController extends Controller
{
    /** Trang chọn ghế */
    public function create(Showtime $showtime)
    {
        // Phim & phòng
        $movie = DB::table('movies')->where('id', $showtime->movie_id)->first();
        $room  = DB::table('rooms')->where('id', $showtime->room_id)->first();

        // Ghế
        $seats = DB::table('seats as s')
            ->leftJoin('seat_types as t','t.id','=','s.seat_type_id')
            ->where('s.room_id', $showtime->room_id)
            ->select('s.id','s.row_letter','s.seat_number','s.code','s.seat_type_id','t.name as seat_type')
            ->orderBy('s.row_letter')->orderBy('s.seat_number')
            ->get();

        // Ghế đã giữ/đặt
        $occupied = DB::table('tickets')
            ->where('showtime_id', $showtime->id)
            ->where('status','!=','canceled')
            ->pluck('seat_id')->toArray();

        // Giá hiệu lực (ưu tiên showtime_prices, fallback seat_types.base_price)
        $sp = DB::table('showtime_prices')
            ->where('showtime_id', $showtime->id)
            ->select('seat_type_id','base_price','price','final_price')
            ->get();

        $effective = [];
        foreach ($sp as $r) {
            $effective[$r->seat_type_id] = !is_null($r->final_price)
                ? (int) $r->final_price
                : (int) ($r->base_price ?? 0) + (int) ($r->price ?? 0);
        }
        if (empty($effective)) {
            $effective = DB::table('seat_types')
                ->pluck('base_price','id')
                ->map(fn($v)=>(int)$v)->toArray();
        }
        $priceMap = $effective;

        // Loại ghế để hiển thị (không ảnh hưởng định giá ghế cụ thể)
        $ticketTypes = DB::table('seat_types')
            ->select('id','name','base_price as display_price')
            ->orderBy('id')->get();

        // Voucher đang hoạt động (lọc bằng SQL, KHÔNG gọi ->filter->isActive())
        $vouchers = Voucher::query()
            ->where('status', 'active')
            ->where(function ($q) {
                $now = now();
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function ($q) {
                $now = now();
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count','<','usage_limit');
            })
            ->orderByDesc('id')
            ->get();

        // Suất chiếu khác
        $otherShowtimes = DB::table('showtimes as st')
            ->join('rooms as rm','rm.id','=','st.room_id')
            ->where('st.movie_id',$showtime->movie_id)
            ->whereDate('st.start_time','>=',now()->toDateString())
            ->select('st.id','st.start_time','rm.name as room_name')
            ->orderBy('st.start_time')->get();

        return view('tickets.create', compact(
            'showtime','movie','room',
            'seats','occupied','priceMap',
            'otherShowtimes','ticketTypes','vouchers'
        ));
    }

    /** Lưu vé */
    public function store(Showtime $showtime, Request $request)
    {
        $data = $request->validate([
            'seat_ids'     => ['required','array','min:1','max:10'],
            'seat_ids.*'   => ['integer'],
            'seat_type_id' => ['required','integer'], // chỉ đồng bộ form, KHÔNG dùng định giá
            'voucher_id'   => ['nullable','integer'],
            'pay_now'      => ['nullable','boolean'],
        ]);

        $userId = Auth::id();
        $status = $request->boolean('pay_now') ? 'paid' : 'reserved';

        // Map giá hiệu lực (ưu tiên showtime_prices)
        $sp = DB::table('showtime_prices')
            ->where('showtime_id', $showtime->id)
            ->select('seat_type_id','base_price','price','final_price')->get();

        $effective = [];
        foreach ($sp as $r) {
            $effective[$r->seat_type_id] = !is_null($r->final_price)
                ? (int)$r->final_price
                : (int)($r->base_price ?? 0) + (int)($r->price ?? 0);
        }
        if (empty($effective)) {
            $effective = DB::table('seat_types')
                ->pluck('base_price','id')
                ->map(fn($v)=>(int)$v)->toArray();
        }

        $voucherId  = $data['voucher_id'] ?? null;
        $voucherUse = 0;

        try {
            DB::transaction(function () use ($data, $showtime, $userId, $status, $effective, $voucherId, &$voucherUse) {

                // Khoá kiểm tra ghế trùng
                $exists = DB::table('tickets')
                    ->where('showtime_id', $showtime->id)
                    ->whereIn('seat_id', $data['seat_ids'])
                    ->where('status','!=','canceled')
                    ->lockForUpdate()
                    ->exists();
                if ($exists) {
                    throw ValidationException::withMessages([
                        'seat_ids' => 'Một hoặc nhiều ghế đã bị người khác giữ chỗ. Vui lòng chọn lại.'
                    ]);
                }

                // Lấy chi tiết ghế
                $rows = DB::table('seats')
                    ->whereIn('id', $data['seat_ids'])
                    ->select('id','seat_type_id','row_letter','seat_number')
                    ->lockForUpdate()
                    ->get();

                // Khoá & kiểm tra voucher trong transaction
                $voucher = null;
                if (!empty($voucherId)) {
                    $voucher = Voucher::whereKey($voucherId)->lockForUpdate()->first();
                    if (!$voucher) {
                        throw ValidationException::withMessages([
                            'voucher_id' => 'Mã ưu đãi không tồn tại.'
                        ]);
                    }
                    // Tự kiểm tra active thay cho isActive() (tránh lệch kiểu dữ liệu)
                    $now = now();
                    $active =
                        $voucher->status === 'active' &&
                        (is_null($voucher->start_at) || $voucher->start_at <= $now) &&
                        (is_null($voucher->end_at)   || $voucher->end_at   >= $now) &&
                        (is_null($voucher->usage_limit) || $voucher->used_count < $voucher->usage_limit);

                    if (!$active) {
                        throw ValidationException::withMessages([
                            'voucher_id' => 'Mã ưu đãi không hợp lệ hoặc đã hết hạn/quá lượt.'
                        ]);
                    }
                }

                // Tính subtotal (tổng giá gốc theo từng ghế thực)
                $subtotal = 0;
                foreach ($rows as $r) {
                    $subtotal += (int)($effective[$r->seat_type_id] ?? 0);
                }

                // Kiểm tra min_order nếu có voucher
                if ($voucher && !is_null($voucher->min_order) && $subtotal < (int)$voucher->min_order) {
                    throw ValidationException::withMessages([
                        'voucher_id' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã ưu đãi.'
                    ]);
                }

                // Tạo vé
                foreach ($rows as $row) {
                    $base  = (int)($effective[$row->seat_type_id] ?? 0);
                    $final = $base;

                    if ($voucher) {
                        if ($voucher->type === 'percent') {
                            $final = max((int) round($base * (100 - (int)$voucher->value) / 100), 0);
                        } else { // fixed
                            $final = max($base - (int)$voucher->value, 0);
                        }
                        $voucherUse++;
                    }

                    DB::table('tickets')->insert([
                        'showtime_id' => $showtime->id,
                        'user_id'     => $userId,
                        'seat_id'     => $row->id,
                        'ticket_type' => 'default',
                        'status'      => $status,
                        'price'       => $base,
                        'final_price' => $final,
                        'voucher_id'  => $voucher->id ?? null,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }

                // Cập nhật used_count theo số ghế được áp mã
                if ($voucher && $voucherUse > 0) {
                    $voucher->increment('used_count', $voucherUse);
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('tickets.history')
            ->with('ok', $status === 'paid'
                ? 'Thanh toán thành công!'
                : 'Đặt vé thành công! Bạn có thể thanh toán trong mục “Vé của tôi”.');
    }
}
