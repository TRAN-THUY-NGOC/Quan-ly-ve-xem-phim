@extends('layouts.app')
@section('title','Chọn ghế')

@push('styles')
<style>
  .seat-grid { display:grid; grid-template-columns: repeat(10, 1fr); gap:8px; }
  .seat { padding:10px 0; text-align:center; border-radius:8px; border:1px solid #ddd; cursor:pointer; user-select:none; }
  .seat.taken { background:#f1f1f1; color:#aaa; cursor:not-allowed; }
  .seat.selected { background:#0d6efd; color:#fff; border-color:#0d6efd; }
  .screen { background:#eee; text-align:center; padding:8px; border-radius:8px; margin-bottom:12px; font-weight:600; }
</style>
@endpush

@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0 fw-bold">{{ $st->movie_title }}</h4>
    <div class="text-muted">
      Phòng <b>{{ $st->room_name }}</b> — {{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i d/m/Y') }}
    </div>
  </div>

  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <div class="card">
    <div class="card-body">
      <div class="screen">MÀN HÌNH</div>

      <div class="seat-grid" id="seatGrid">
        @php
          $takenIds = collect($taken)->flip(); // map nhanh
        @endphp
        @foreach($seats as $s)
          @php
            $id = $s->id;
            $label = $s->label;
            $isTaken = $takenIds->has($id);
          @endphp
          <div class="seat {{ $isTaken ? 'taken' : '' }}" data-id="{{ $id }}">
            {{ $label }}
          </div>
        @endforeach
      </div>

      <form class="mt-3 d-flex align-items-center gap-2" method="post" action="{{ route('tickets.store',$st->id) }}">
        @csrf
        <input type="hidden" id="seat_id" name="seat_id">
        <span>Giá: <b>{{ number_format($basePrice,0,',','.') }} đ</b></span>
        <button class="btn btn-primary" id="btnBook" disabled>Đặt vé</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const grid = document.getElementById('seatGrid');
  const seatInput = document.getElementById('seat_id');
  const btnBook = document.getElementById('btnBook');

  grid.addEventListener('click', (e) => {
    const el = e.target.closest('.seat');
    if (!el || el.classList.contains('taken')) return;

    // toggle single select
    grid.querySelectorAll('.seat.selected').forEach(x => x.classList.remove('selected'));
    el.classList.add('selected');
    seatInput.value = el.dataset.id;
    btnBook.disabled = false;
  });
</script>
@endpush
@endsection
