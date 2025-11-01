@extends('layouts.app')
@section('title','Đặt vé')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-1">{{ $movie->title }}</h4>
      <div class="text-muted">
        Phòng <b>{{ $room->name }}</b> —
        {{ \Illuminate\Support\Carbon::parse($showtime->start_time)->format('H:i d/m/Y') }}
      </div>
    </div>
    <div>
      <a href="{{ route('movies.show',$movie->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Quay lại phim
      </a>
    </div>
  </div>

  <div class="row g-4">
    {{-- Cột trái: chọn loại vé + sơ đồ ghế --}}
    <div class="col-lg-8">
      {{-- Chọn loại vé (hiển thị giá theo loại ghế) --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-bold text-uppercase mb-2">Chọn loại vé</h6>
          <div class="row g-2 small">
            @foreach($priceMap as $typeId => $price)
              <div class="col-sm-6 col-md-4">
                <div class="p-2 border rounded d-flex justify-content-between">
                  <span>Loại #{{ $typeId }}</span>
                  <b>{{ number_format($price) }} đ</b>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Sơ đồ ghế --}}
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold text-uppercase text-center mb-3">Chọn ghế</h6>
          <div class="text-center small text-muted mb-2">Màn hình</div>

          <form id="seatForm" method="POST" action="{{ route('tickets.store',$showtime) }}">
            @csrf
            <div class="seat-grid mx-auto">
              @php
                // group seats by row
                $groups = $seats->groupBy('row_letter');
              @endphp

              @foreach($groups as $row => $items)
                <div class="seat-row">
                  <div class="seat-row-label">{{ $row }}</div>
                  @foreach($items as $s)
                    @php
                      $isTaken = in_array($s->id,$occupied, true);
                      $price   = $priceMap[$s->seat_type_id] ?? 0;
                    @endphp
                    <button
                      type="button"
                      class="seat {{ $isTaken ? 'taken' : '' }}"
                      data-id="{{ $s->id }}"
                      data-price="{{ $price }}"
                      title="{{ ($s->code ?: $s->row_letter.$s->seat_number) }} • {{ number_format($price) }}đ"
                      {{ $isTaken ? 'disabled' : '' }}
                    >
                      {{ $s->seat_number }}
                    </button>
                  @endforeach
                </div>
              @endforeach
            </div>

            {{-- input ẩn để submit danh sách ghế --}}
            <div id="hiddenInputs"></div>
          </form>

          {{-- Chú thích --}}
          <div class="row mt-3 g-2 small">
            <div class="col-auto d-flex align-items-center"><span class="legend seat me-2"></span> Ghế trống</div>
            <div class="col-auto d-flex align-items-center"><span class="legend seat selected me-2"></span> Ghế đang chọn</div>
            <div class="col-auto d-flex align-items-center"><span class="legend seat taken me-2"></span> Ghế đã đặt</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Cột phải: tóm tắt & thanh toán --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm position-sticky" style="top: 1rem">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Tóm tắt</h6>
          <div class="mb-1 small text-muted">Phim</div>
          <div class="fw-semibold mb-2">{{ $movie->title }}</div>

          <div class="mb-1 small text-muted">Thời gian</div>
          <div class="mb-2">{{ \Illuminate\Support\Carbon::parse($showtime->start_time)->format('H:i d/m/Y') }}</div>

          <div class="mb-1 small text-muted">Ghế đã chọn</div>
          <div id="seatList" class="mb-2">—</div>

          <hr class="my-2">
          <div class="d-flex justify-content-between">
            <div class="fw-bold">Tổng</div>
            <div class="fw-bold" id="totalText">0 đ</div>
          </div>

          <button form="seatForm" class="btn btn-primary w-100 mt-3" id="btnPay" disabled>
            Đặt vé
          </button>
          <div class="text-muted small mt-2">* Vé ở trạng thái <b>giữ chỗ</b>. Bạn có thể thanh toán trong mục “Vé của tôi”.</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- CSS tối giản --}}
<style>
  .seat-grid{display:inline-block;padding:10px;background:#f8fafc;border-radius:12px}
  .seat-row{display:flex;align-items:center;gap:.35rem;margin:.25rem 0}
  .seat-row-label{width:24px;text-align:center;font-weight:600;color:#475569}
  .seat{
    width:28px;height:28px;border-radius:6px;border:1px solid #cbd5e1;background:#fff;
    display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;
    transition:.15s; user-select:none;
  }
  .seat:hover{transform:translateY(-2px);box-shadow:0 2px 8px rgba(0,0,0,.08)}
  .seat.selected{background:#2563eb;color:#fff;border-color:#1d4ed8}
  .seat.taken{background:#e2e8f0;color:#94a3b8;border-color:#cbd5e1;cursor:not-allowed}
  .legend.seat{width:18px;height:18px}
</style>

{{-- JS chọn ghế + tính tiền --}}
<script>
  (function(){
    const seatBtns = document.querySelectorAll('.seat:not(.taken)');
    const listBox  = document.getElementById('seatList');
    const totalTxt = document.getElementById('totalText');
    const hidden   = document.getElementById('hiddenInputs');
    const payBtn   = document.getElementById('btnPay');

    let selected = []; // {id, price}

    function render(){
      // list
      if(selected.length === 0){
        listBox.textContent = '—';
        payBtn.disabled = true;
      }else{
        listBox.textContent = selected.map(x => x.label || x.id).join(', ');
        payBtn.disabled = false;
      }
      // total
      const total = selected.reduce((s,x)=> s + Number(x.price||0), 0);
      totalTxt.textContent = new Intl.NumberFormat('vi-VN').format(total) + ' đ';

      // hidden inputs
      hidden.innerHTML = '';
      selected.forEach(x => {
        const i = document.createElement('input');
        i.type  = 'hidden';
        i.name  = 'seat_ids[]';
        i.value = x.id;
        hidden.appendChild(i);
      });
    }

    seatBtns.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const id = Number(btn.dataset.id);
        const price = Number(btn.dataset.price);
        btn.classList.toggle('selected');

        if(btn.classList.contains('selected')){
          selected.push({id, price, label: btn.title.split(' • ')[0]});
        }else{
          selected = selected.filter(x => x.id !== id);
        }
        render();
      });
    });

    render();
  })();
</script>
@endsection
