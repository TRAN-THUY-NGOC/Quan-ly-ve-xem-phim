<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;

class ShowtimesController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['movie','room'])->orderByDesc('start_time')->paginate(15);
        // return view('admin.showtimes.index', compact('showtimes'));
        return response('admin.showtimes.index (demo) '.$showtimes->count().' rows');
    }

    public function create()
    {
        // return view('admin.showtimes.form', ['showtime'=>new Showtime(),'movies'=>Movie::orderBy('title')->get(),'rooms'=>Room::orderBy('name')->get()]);
        return response('admin.showtimes.create (demo)');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        // chặn chồng giờ trong cùng phòng
        $overlap = Showtime::where('room_id',$data['room_id'])
            ->where(function($q) use ($data){
                $q->whereBetween('start_time', [$data['start_time'],$data['end_time']])
                  ->orWhereBetween('end_time',   [$data['start_time'],$data['end_time']])
                  ->orWhere(function($q2) use ($data){
                      $q2->where('start_time','<=',$data['start_time'])
                         ->where('end_time','>=',$data['end_time']);
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors('Phòng đã có suất trùng/chéo giờ.')->withInput();
        }

        Showtime::create($data);
        return redirect()->route('admin.showtimes.index')->with('ok','Đã tạo suất chiếu.');
    }

    public function edit(Showtime $showtime)
    {
        // return view('admin.showtimes.form', ['showtime'=>$showtime,'movies'=>Movie::orderBy('title')->get(),'rooms'=>Room::orderBy('name')->get()]);
        return response('admin.showtimes.edit (demo) id='.$showtime->id);
    }

    public function update(Request $r, Showtime $showtime)
    {
        $data = $r->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $overlap = Showtime::where('room_id',$data['room_id'])
            ->where('id','<>',$showtime->id)
            ->where(function($q) use ($data){
                $q->whereBetween('start_time', [$data['start_time'],$data['end_time']])
                  ->orWhereBetween('end_time',   [$data['start_time'],$data['end_time']])
                  ->orWhere(function($q2) use ($data){
                      $q2->where('start_time','<=',$data['start_time'])
                         ->where('end_time','>=',$data['end_time']);
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors('Phòng đã có suất trùng/chéo giờ.')->withInput();
        }

        $showtime->update($data);
        return redirect()->route('admin.showtimes.index')->with('ok','Đã cập nhật suất chiếu.');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return back()->with('ok','Đã xóa suất chiếu.');
    }
}
