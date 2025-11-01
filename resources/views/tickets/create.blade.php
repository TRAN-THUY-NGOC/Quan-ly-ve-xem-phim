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

      {{-- CHỌN LOẠI VÉ + ĐỔI SUẤT CHIẾU --}}
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-end">
            <h6 class="fw-bold text-uppercase mb-2">Chọn loại vé</h6>

            <div class="d-flex align-items-center gap-2">
              <label class="small text-muted mb-0">Đổi suất chiếu:</label>
              <select id="switchShowtime" class="form-select form-select-sm">
                <option value="{{ $showtime->id }}" selected>
                  {{ \Illuminate\Support\Carbon::parse($showtime->start_time)->format('H:i d/m/Y') }} — Phòng {{ $room->name }} (hiện tại)
                </option>
                @foreach($otherShowtimes as $st)
                  @if($st->id != $showtime->id)
                    <option value="{{ $st->id }}">
                      {{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i d/m/Y') }} — Phòng {{ $st->room_name }}
                    </option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>

          {{-- Radio loại vé --}}
          <div class="row g-2 small mt-2" id="ticketTypeGroup">
            @foreach($ticketTypes as $key => $tp)
              <div class="col-auto">
                <input class="btn-check" type="radio" name="ticket_type_radio" id="tt-{{ $key }}"
                       value="{{ $key }}" data-coef="{{ $tp['coef'] }}"
                       {{ $key === $defaultType ? 'checked' : '' }}>
                <label class="btn btn-outline-dark btn-sm" for="tt-{{ $key }}">
                  {{ $tp['label'] }} (x{{ rtrim(rtrim(number_format($tp['coef'],2,'.',''), '0'), '.') }})
                </label>
              </div>
            @endforeach
          </div>

          {{-- Bảng giá theo loại ghế (auto cập nhật theo loại vé) --}}
          <div class="row g-2 small mt-3" id="seatTypePricePreview">
            @foreach($priceMap as $typeId => $price)
              <div class="col-sm-6 col-md-4">
                <div class="p-2 border rounded d-flex justify-content-between">
                  <span>Loại ghế #{{ $typeId }}</span>
                  <b class="preview-price" data-base="{{ $price }}">{{ number_format($price) }} đ</b>
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
            <input type="hidden" name="ticket_type" id="ticketTypeInput" value="{{ $defaultType }}">
            <div class="seat-grid mx-auto">
              @php $groups = $seats->groupBy('row_letter'); @endphp

              @foreach($groups as $row => $items)
                <div class="seat-row">
                  <div class="seat-row-label">{{ $row }}</div>
                  @foreach($items as $s)
                    @php
                      $isTaken = in_array($s->id,$occupied, true);
                      $price   = $priceMap[$s->seat_type_id] ?? 0; // giá gốc theo seat_type
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

    {{-- Cột phải: tóm tắt & đặt vé --}}
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

{{-- CSS nhỏ gọn --}}
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

{{-- JS: đổi suất chiếu + chọn loại vé + tính tiền --}}
<script>
  // Đổi suất chiếu
  document.getElementById('switchShowtime')?.addEventListener('change', function(){
    if (!this.value) return;
    if (this.value == '{{ $showtime->id }}') return;
    window.location.href = "{{ url('/showtimes') }}/" + this.value + "/tickets/create";
  });

  (function(){
    const seatBtns = document.querySelectorAll('.seat:not(.taken)');
    const listBox  = document.getElementById('seatList');
    const totalTxt = document.getElementById('totalText');
    const hidden   = document.getElementById('hiddenInputs');
    const payBtn   = document.getElementById('btnPay');

    // Ticket type
    const typeRadios   = document.querySelectorAll('input[name="ticket_type_radio"]');
    const typeInput    = document.getElementById('ticketTypeInput');
    const previewNodes = document.querySelectorAll('#seatTypePricePreview .preview-price');

    let coef = parseFloat(document.querySelector('input[name="ticket_type_radio"]:checked')?.dataset.coef || '1');
    let selected = []; // {id, priceBase, label}

    function renderPreviewPrices(){
      previewNodes.forEach(el => {
        const base = parseFloat(el.dataset.base || '0');
        el.textContent = new Intl.NumberFormat('vi-VN').format(Math.round(base * coef)) + ' đ';
      });
    }

    function render(){
      // danh sách ghế
      listBox.textContent = selected.length ? selected.map(x => x.label).join(', ') : '—';
      payBtn.disabled = selected.length === 0;

      // tổng tiền = Σ(base * coef)
      const total = selected.reduce((sum,x)=> sum + Math.round((x.priceBase||0) * coef), 0);
      totalTxt.textContent = new Intl.NumberFormat('vi-VN').format(total) + ' đ';

      // hidden inputs
      hidden.innerHTML = '';
      selected.forEach(x => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'seat_ids[]'; i.value = x.id;
        hidden.appendChild(i);
      });
    }

    seatBtns.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const id   = Number(btn.dataset.id);
        const base = Number(btn.dataset.price);
        btn.classList.toggle('selected');

        if(btn.classList.contains('selected')){
          selected.push({id, priceBase: base, label: btn.title.split(' • ')[0]});
        }else{
          selected = selected.filter(x => x.id !== id);
        }
        render();
      });
    });

    // đổi loại vé
    typeRadios.forEach(r => {
      r.addEventListener('change', ()=>{
        if(!r.checked) return;
        coef = parseFloat(r.dataset.coef || '1');
        typeInput.value = r.value;
        renderPreviewPrices();
        render();
      });
    });

    renderPreviewPrices();
    render();
  })();
</script>
@endsection
