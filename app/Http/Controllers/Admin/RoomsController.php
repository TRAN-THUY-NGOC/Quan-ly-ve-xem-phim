<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoomsController extends Controller
{
    public function index()
    {
        // Lấy phòng + đếm số ghế (không đòi hỏi quan hệ Eloquent)
        $rooms = Room::orderBy('name')
            ->paginate(15);

        // map thêm seats_count cho từng phòng
        $roomIds = $rooms->pluck('id');
        $seatCounts = DB::table('seats')
            ->select('room_id', DB::raw('COUNT(*) as c'))
            ->whereIn('room_id', $roomIds)
            ->groupBy('room_id')
            ->pluck('c', 'room_id');

        foreach ($rooms as $r) {
            $r->seats_count = $seatCounts[$r->id] ?? 0;
        }

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.form', [
            'room' => new Room(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $r)
    {
        $r->validate([
            'name'     => ['required','string','max:100', Rule::unique('rooms','name')],
            'capacity' => ['required','integer','min:1','max:1000'],
        ]);

        Room::create($r->only('name','capacity'));

        return redirect()->route('admin.rooms.index')->with('ok', 'Đã tạo phòng chiếu.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.form', [
            'room' => $room,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $r, Room $room)
    {
        $r->validate([
            'name'     => ['required','string','max:100', Rule::unique('rooms','name')->ignore($room->id)],
            'capacity' => ['required','integer','min:1','max:1000'],
        ]);

        $room->update($r->only('name','capacity'));

        return redirect()->route('admin.rooms.index')->with('ok', 'Đã cập nhật phòng chiếu.');
    }

    public function destroy(Room $room)
    {
        // Nếu phòng đã có showtimes hoặc seats có thể cấm xoá, tuỳ chính sách.
        $hasShowtimes = DB::table('showtimes')->where('room_id', $room->id)->exists();
        if ($hasShowtimes) {
            return back()->withErrors('Phòng đã có suất chiếu, không thể xoá.');
        }

        DB::table('seats')->where('room_id', $room->id)->delete();
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('ok', 'Đã xoá phòng chiếu.');
    }

    /**
     * Tạo ghế theo sức chứa / cấu trúc bảng seats.
     * - Nếu seats có (row_letter, seat_number) => tạo A-D, 10 ghế/hàng (đủ capacity).
     * - Nếu seats có (code) => sinh nhãn A1… theo capacity.
     * - Nếu seats có (label) => tương tự code.
     * - Nếu không có các cột trên => báo lỗi hướng dẫn.
     */
    public function generateSeats(Room $room)
    {
        // Xoá ghế cũ của phòng (nếu muốn reset)
        DB::table('seats')->where('room_id', $room->id)->delete();

        $capacity = (int)$room->capacity;
        if ($capacity <= 0) {
            return back()->withErrors('Sức chứa phòng phải > 0.');
        }

        // Lấy loại ghế (mặc định ghế Thường nếu có), nếu không có thì để null seat_type_id
        $seatTypeId = DB::table('seat_types')->where('name','Thường')->value('id');

        // Kiểm tra schema hiện tại của bảng seats
        $hasRow = Schema::hasColumn('seats','row_letter');
        $hasNum = Schema::hasColumn('seats','seat_number');
        $hasCode = Schema::hasColumn('seats','code');
        $hasLabel = Schema::hasColumn('seats','label');

        if ($hasRow && $hasNum) {
            // Tạo theo hàng A, B, C... mỗi hàng 10 ghế cho đến khi đủ capacity
            $rows = range('A','Z');
            $remain = $capacity;
            foreach ($rows as $row) {
                $perRow = min(10, $remain);
                if ($perRow <= 0) break;

                for ($n=1; $n <= $perRow; $n++) {
                    DB::table('seats')->insert([
                        'room_id'      => $room->id,
                        'row_letter'   => $row,
                        'seat_number'  => $n,
                        'seat_type_id' => $seatTypeId,
                    ]);
                }
                $remain -= $perRow;
            }
        } elseif ($hasCode) {
            // Sinh code A1, A2 … đủ capacity
            $rows = range('A','Z');
            $remain = $capacity;
            foreach ($rows as $row) {
                $perRow = min(10, $remain);
                if ($perRow <= 0) break;

                for ($n=1; $n <= $perRow; $n++) {
                    DB::table('seats')->insert([
                        'room_id'      => $room->id,
                        'code'         => $row.$n,
                        'seat_type_id' => $seatTypeId,
                    ]);
                }
                $remain -= $perRow;
            }
        } elseif ($hasLabel) {
            // Sinh label A1, A2 … đủ capacity
            $rows = range('A','Z');
            $remain = $capacity;
            foreach ($rows as $row) {
                $perRow = min(10, $remain);
                if ($perRow <= 0) break;

                for ($n=1; $n <= $perRow; $n++) {
                    DB::table('seats')->insert([
                        'room_id'      => $room->id,
                        'label'        => $row.$n,
                        'seat_type_id' => $seatTypeId,
                    ]);
                }
                $remain -= $perRow;
            }
        } else {
            // Không nhận diện được schema ghế
            return back()->withErrors(
                "Không thể tạo ghế: bảng seats thiếu các cột quen thuộc (row_letter/seat_number hoặc code/label). ".
                "Vui lòng bổ sung 1 trong các cấu trúc cột này."
            );
        }

        return back()->with('ok', "Đã tạo ghế cho phòng {$room->name} (tối đa {$capacity}).");
    }
}
