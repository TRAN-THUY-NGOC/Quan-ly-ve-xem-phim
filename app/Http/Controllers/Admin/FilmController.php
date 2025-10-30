<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;

class FilmController extends Controller
{
    // =============================
    // DANH SÁCH PHIM
    // =============================
    public function index(Request $request)
    {
        $query = Film::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('film_code', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Lọc thể loại
        if ($request->filled('genre')) {
            $query->where('genre', 'LIKE', "%{$request->genre}%");
        }

        // Lọc trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sắp xếp
        switch ($request->sort) {
            case 'id_desc': $query->orderBy('id', 'desc'); break;
            case 'id_asc': $query->orderBy('id', 'asc'); break;
            case 'date_desc': $query->orderBy('created_at', 'desc'); break;
            default: $query->orderBy('id', 'asc'); break;
        }

        $films = $query->paginate(10)->withQueryString();
        $films->onEachSide(1);

        // Lấy danh sách thể loại duy nhất
        $allGenres = Film::pluck('genre')->toArray();
        $genres = collect($allGenres)
            ->flatMap(fn($g) => array_map('trim', explode(',', $g)))
            ->unique()
            ->sort()
            ->values();

        return view('admin.films.index', compact('films', 'genres'));
    }

    // =============================
    // FORM THÊM MỚI
    // =============================
    public function create()
    {
        return view('admin.films.create');
    }

    // =============================
    // LƯU PHIM MỚI
    // =============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_code'     => 'nullable|string|max:20',
            'title'         => 'required|string|max:255',
            'genre'         => 'required|string|max:100',
            'director'      => 'nullable|string|max:100',
            'cast'          => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:100',
            'language'      => 'nullable|string|max:100',
            'duration_min'  => 'required|integer|min:1',
            'release_date'  => 'required|date',
            'ticket_price'  => 'nullable|numeric|min:0',
            'status'        => 'required|in:active,upcoming,inactive',
            'description'   => 'nullable|string',
            'trailer_url'   => 'nullable|string',
            'image'         => 'nullable|string',
            'poster'        => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        // Upload poster (nếu có)
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        // Nếu chưa có mã phim -> tự tạo
        if (empty($validated['film_code'])) {
            $validated['film_code'] = 'F' . str_pad((Film::max('id') + 1), 3, '0', STR_PAD_LEFT);
        }

        Film::create($validated);

        return redirect()->route('admin.films.index')->with('success', '🎬 Thêm phim mới thành công!');
    }

    // =============================
    // XEM CHI TIẾT PHIM
    // =============================
    public function show($id)
    {
        $film = Film::findOrFail($id);
        return view('admin.films.show', compact('film'));
    }

    // =============================
    // FORM CHỈNH SỬA
    // =============================
    public function edit($id)
    {
        $film = Film::findOrFail($id);
        return view('admin.films.edit', compact('film'));
    }

    // =============================
    // CẬP NHẬT PHIM
    // =============================
    public function update(Request $request, $id)
    {
        $film = Film::findOrFail($id);

        $validated = $request->validate([
            'film_code'     => 'nullable|string|max:20',
            'title'         => 'required|string|max:255',
            'genre'         => 'required|string|max:100',
            'director'      => 'nullable|string|max:100',
            'cast'          => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:100',
            'language'      => 'nullable|string|max:100',
            'duration_min'  => 'required|integer|min:1',
            'release_date'  => 'required|date',
            'ticket_price'  => 'nullable|numeric|min:0',
            'status'        => 'required|in:active,upcoming,inactive',
            'description'   => 'nullable|string',
            'trailer_url'   => 'nullable|string',
            'image'         => 'nullable|string',
            'poster'        => 'nullable|image|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        $film->update($validated);

        return redirect()->route('admin.films.show', $film->id)->with('success', '✅ Cập nhật phim thành công!');
    }

    // =============================
    // XÓA PHIM
    // =============================
    public function destroy($id)
    {
        $film = Film::findOrFail($id);
        $film->delete();

        return redirect()->route('admin.films.index')->with('success', '🗑️ Đã xóa phim thành công!');
    }
}
