<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cinema;


class RoomController extends Controller
{
    // ✅ Danh sách phòng chiếu
    public function index(Request $request)
    {
        $query = Room::with('cinema');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%")
                  ->orWhereHas('cinema', function($sub) use ($keyword) {
                      $sub->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seat_type')) {
            $query->where('seat_type', $request->seat_type);
        }

        if ($request->filled('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }

        $rooms = $query->orderBy('id', 'desc')->paginate(5);
        $cinemas = Cinema::all();

        return view('admin.rooms.index', compact('rooms', 'cinemas'));
    }

    // ✅ Trang thêm mới
    public function create()
    {
        $cinemas = Cinema::all();
        return view('admin.rooms.create', compact('cinemas'));
    }

    // ✅ Lưu dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:rooms,code|max:10',
            'name' => 'required|max:100',
            'cinema_id' => 'required|exists:cinemas,id',
            'seat_count' => 'required|integer|min:10',
            'seat_type' => 'required',
            'status' => 'required',
        ]);

        Room::create($request->only(['code', 'name', 'cinema_id', 'seat_count', 'seat_type', 'status']));

        return redirect()->route('admin.rooms.index')->with('success', '✅ Thêm phòng chiếu thành công!');
    }

    // ✅ Chỉnh sửa
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $cinemas = Cinema::all();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }

    // ✅ Cập nhật
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'code' => 'required|max:10|unique:rooms,code,' . $id,
            'name' => 'required|max:100',
            'cinema_id' => 'required|exists:cinemas,id',
            'seat_count' => 'required|integer|min:10',
            'seat_type' => 'required',
            'status' => 'required',
        ]);

        $room->update($request->only(['code', 'name', 'cinema_id', 'seat_count', 'seat_type', 'status']));

        return redirect()->route('admin.rooms.index')->with('success', '✅ Cập nhật phòng chiếu thành công!');
    }

    // ✅ Xóa
    public function destroy($id)
    {
        Room::findOrFail($id)->delete();
        return redirect()->route('admin.rooms.index')->with('success', '🗑️ Xóa phòng chiếu thành công!');
    }

    // ✅ Thống kê ghế theo loại trong từng phòng
    public function seatStatistics()
    {
        // Lấy danh sách phòng, mỗi phòng có các loại ghế
        $data = \DB::table('seats')
            ->join('rooms', 'seats.room_id', '=', 'rooms.id')
            ->join('seat_types', 'seats.seat_type_id', '=', 'seat_types.id')
            ->select(
                'rooms.name as room_name',
                'seat_types.name as seat_type',
                \DB::raw('COUNT(seats.id) as total'),
                \DB::raw("SUM(CASE WHEN seats.status = 'available' THEN 1 ELSE 0 END) as available_count"),
                \DB::raw("SUM(CASE WHEN seats.status = 'booked' THEN 1 ELSE 0 END) as booked_count"),
                \DB::raw("SUM(CASE WHEN seats.status = 'broken' THEN 1 ELSE 0 END) as broken_count")
            )
            ->groupBy('rooms.id', 'seat_types.id')
            ->orderBy('rooms.id')
            ->get();

        return view('admin.rooms.statistics', compact('data'));
    }

}
