<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('name')->paginate(12);
        // return view('admin.rooms.index', compact('rooms'));
        return response('admin.rooms.index (demo) '.$rooms->count().' rows');
    }

    public function create()
    {
        // return view('admin.rooms.form', ['room'=>new Room()]);
        return response('admin.rooms.create (demo)');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'     => 'required|string|max:100|unique:rooms,name',
            'capacity' => 'required|integer|min:1|max:500',
        ]);
        Room::create($data);
        return redirect()->route('admin.rooms.index')->with('ok','Đã tạo phòng.');
    }

    public function edit(Room $room)
    {
        // return view('admin.rooms.form', compact('room'));
        return response('admin.rooms.edit (demo) id='.$room->id);
    }

    public function update(Request $r, Room $room)
    {
        $data = $r->validate([
            'name'     => 'required|string|max:100|unique:rooms,name,'.$room->id,
            'capacity' => 'required|integer|min:1|max:500',
        ]);
        $room->update($data);
        return redirect()->route('admin.rooms.index')->with('ok','Đã cập nhật phòng.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('ok','Đã xóa phòng.');
    }

    // Sinh ghế mặc định: A..F x 10 ghế; 4-7 là VIP nếu có seat_types.
    public function generateSeats(Room $room)
    {
        $vipId = SeatType::where('name','VIP')->value('id') ?? null;
        $stdId = SeatType::where('name','Standard')->orWhere('name','Thường')->value('id') ?? null;

        DB::transaction(function () use ($room, $vipId, $stdId) {
            foreach (range('A','F') as $row) {
                foreach (range(1,10) as $n) {
                    Seat::updateOrCreate(
                        ['room_id'=>$room->id, 'row_letter'=>$row, 'seat_number'=>$n],
                        ['seat_type_id'=> ($vipId && $stdId) ? (($n>=4 && $n<=7) ? $vipId : $stdId) : ($stdId ?? $vipId)]
                    );
                }
            }
        });

        $room->update(['capacity'=> Seat::where('room_id',$room->id)->count() ]);
        return back()->with('ok','Đã sinh ghế cho phòng '.$room->name);
    }
}
