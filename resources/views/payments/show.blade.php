@extends('layouts.app')
@section('title','Thanh toán')

@section('content')
<div class="container py-3">
  <div class="card">
    <div class="card-body">
      <h5 class="fw-bold mb-2">{{ $t->movie_title }}</h5>
      <div class="text-muted mb-2">Phòng {{ $t->room_name }} — {{ \Illuminate\Support\Carbon::parse($t->start_time)->format('H:i d/m/Y') }}</div>
      <div class="mb-3">Ghế: <b>{{ $t->seat_label }}</b></div>
      <div class="mb-3">Mã vé (QR/Code): <code>{{ $t->qr_code }}</code></div>
      <div class="mb-3">Tổng tiền: <b>{{ number_format($t->final_price,0,',','.') }} đ</b></div>
      <div class="mb-3">Trạng thái: <span class="badge {{ $t->status==='paid'?'bg-success':'bg-warning text-dark' }}">{{ strtoupper($t->status) }}</span></div>

      @if($t->status!=='paid')
        <form method="post" action="{{ route('payments.complete', $t->id) }}">
          @csrf
          <div class="d-flex gap-2">
            <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Thanh toán (Demo)</button>
            <a href="{{ route('tickets.history') }}" class="btn btn-outline-secondary">Để sau</a>
          </div>
        </form>
      @else
        <a href="{{ route('tickets.history') }}" class="btn btn-primary">Về lịch sử vé</a>
      @endif
    </div>
  </div>
</div>
@endsection
