<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;


class ShowtimeController extends Controller
{
    public function show($id)
{
    $showtime = Showtime::findOrFail($id);
    return view('admin.showtimes.show', compact('showtime'));
}

    public function index(Request $request)
{
    $query = Showtime::with('film');

    if ($request->filled('keyword')) {
        $query->whereHas('film', function ($q) use ($request) {
            $q->where('title', 'like', '%'.$request->keyword.'%');
        })->orWhere('cinema', 'like', '%'.$request->keyword.'%');
    }

    if ($request->filled('cinema')) {
        $query->where('cinema', $request->cinema);
    }

    if ($request->filled('status')) {
        $now = now();
        if ($request->status == 'upcoming') {
            $query->where('date', '>=', $now);
        } else {
            $query->where('date', '<', $now);
        }
    }

    $showtimes = $query->orderBy('date', 'desc')->paginate(10);
    $cinemas = Showtime::select('cinema')->distinct()->pluck('cinema');

    return view('admin.showtimes.index', compact('showtimes', 'cinemas'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|integer|exists:films,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'cinema' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0|max:' . $request->total_seats,
        ]);

        // Tạo suất chiếu mới
        Showtime::create([
            'film_id' => $validated['film_id'],
            'date' => $validated['date'],
            'price' => $validated['price'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'cinema' => $validated['cinema'],
            'room' => $validated['room'],
            'total_seats' => $validated['total_seats'],
            'available_seats' => $validated['available_seats'],
        ]);

        return redirect()
            ->route('admin.showtimes.index')
            ->with('success', 'Thêm suất chiếu thành công!');
    }
}
