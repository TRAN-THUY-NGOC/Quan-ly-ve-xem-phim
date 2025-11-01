@extends('layouts.app')
@section('title','Đặt vé')

@section('content')
<div class="container py-4">

  {{-- Header + đổi suất chiếu --}}
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-1">{{ $movie->title }}</h4>
      <div class="text-muted">
        Phòng <b>{{ $room->name }}</b> —
        {{ \Illuminate\Support\Carbon::parse($showtime->start_time)->format('H:i d/m/Y') }}
      </div>
    </div>

    <div class="d-flex gap-2">
      <select class="form-select form-select-sm" onchange="if(this.value) location.href=this.value;">
        @foreach($otherShowtimes as $st)
          @php $url = route('tickets.create',$st->id); @endphp
          <option value="{{ $url }}" {{ $st->id==$showtime->id ? 'selected' : '' }}>
            {{ \Illuminate\Support\Carbon::parse($st->start_time)->format('H:i d/m/Y') }} — {{ $st->room_name }} {{ $st->id==$showtime->id ? '(hiện tại)' : '' }}
          </option>
        @endforeach
      </select>

      <a href="{{ route('movies.show',$movie->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Quay lại phim
      </a>
    </div>
  </div>

  {{-- Chọn loại vé --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <h6 class="fw-bold text-uppercase mb-2">Chọn loại vé</h6>
      <div class="d-flex flex-wrap gap-2">
        @foreach($ticketTypes as $key => $t)
          <button
            type="button"
            class="btn btn-outline-primary btn-sm tt-btn {{ $key===$defaultType ? 'active' : '' }}"
            data-type="{{ $key }}"
            data-coef="{{ $t['coef'] }}"
          >
            {{ $t['label'] }} (x{{ rtrim(rtrim(number_format($t['coef'],2,'.',''), '0'), '.') }})
          </button>
        @endforeach
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- Ghế --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold text-uppercase text-center mb-3">Chọn ghế</h6>
          <div class="text-center small text-muted mb-2">Màn hình</div>

          <form id="seatForm" method="POST" action="{{ route('tickets.store',$showtime) }}">
            @csrf
            <input type="hidden" name="ticket_type" id="ticket_type" value="{{ $defaultType }}">
            {{-- pay_now chỉ thêm khi user nhấn Xác nhận thanh toán --}}
            <div class="seat-grid mx-auto">
              @php $groups = $seats->groupBy('row_letter'); @endphp
              @foreach($groups as $row => $items)
                <div class="seat-row">
                  <div class="seat-row-label">{{ $row }}</div>
                  @foreach($items as $s)
                    @php
                      $isTaken = in_array($s->id,$occupied,true);
                      $price   = $priceMap[$s->seat_type_id] ?? 0;
                      $label   = $s->code ?: ($s->row_letter.$s->seat_number);
                    @endphp
                    <button type="button"
                      class="seat {{ $isTaken ? 'taken' : '' }}"
                      title="{{ $label }} • {{ number_format($price) }}đ"
                      data-id="{{ $s->id }}"
                      data-base="{{ $price }}"
                      {{ $isTaken ? 'disabled' : '' }}
                    >{{ $s->seat_number }}</button>
                  @endforeach
                </div>
              @endforeach
            </div>

            <div id="hiddenInputs"></div>
          </form>

          <div class="row mt-3 g-2 small">
            <div class="col-auto d-flex align-items-center"><span class="legend seat me-2"></span> Ghế trống</div>
            <div class="col-auto d-flex align-items-center"><span class="legend seat selected me-2"></span> Ghế đang chọn</div>
            <div class="col-auto d-flex align-items-center"><span class="legend seat taken me-2"></span> Ghế đã đặt</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Tóm tắt + Thanh toán --}}
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

          <button type="button" class="btn btn-primary w-100 mt-3" id="btnPay" disabled>
            Thanh toán
          </button>
          <div class="text-muted small mt-2">
            * Vé ở trạng thái <b>giữ chỗ</b>. Bạn có thể thanh toán trong mục “Vé của tôi”.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal QR thanh toán --}}
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Thanh toán bằng QR</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-2">Vui lòng quét QR để thanh toán</div>
        <div class="d-flex justify-content-center">
          {{-- QR giả lập (SVG) --}}
          <svg width="220" height="220" viewBox="0 0 220 220">
            <rect x="0" y="0" width="220" height="220" fill="#f2f2f2"/>
            <rect x="15" y="15" width="60" height="60" fill="#000"/>
            <rect x="145" y="15" width="60" height="60" fill="#000"/>
            <rect x="15" y="145" width="60" height="60" fill="#000"/>
            <rect x="85" y="85" width="50" height="50" fill="#000"/>
            <rect x="145" y="145" width="20" height="20" fill="#000"/>
            <rect x="170" y="120" width="35" height="15" fill="#000"/>
            <rect x="120" y="170" width="15" height="35" fill="#000"/>
            <rect x="95" y="25" width="30" height="15" fill="#000"/>
          </svg>
        </div>
        <div class="text-center mt-3">
          <div>Hết hạn sau <b id="countdown">30</b> giây</div>
          <div class="mt-1">Số tiền: <b id="modalAmount">0 đ</b></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnCancelPay" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
        <button id="btnConfirmPay" type="button" class="btn btn-primary">Xác nhận đã thanh toán</button>
      </div>
    </div>
  </div>
</div>

{{-- CSS --}}
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
  .tt-btn.active{color:#fff !important;background:#0d6efd;border-color:#0d6efd}
</style>

{{-- JS --}}
<script>
(function(){
  // Bootstrap modal
  let bsModal;

  let ticketType = document.getElementById('ticket_type').value;
  let coef      = getCoef(ticketType);

  const seatBtns = document.querySelectorAll('.seat:not(.taken)');
  const listBox  = document.getElementById('seatList');
  const totalTxt = document.getElementById('totalText');
  const hidden   = document.getElementById('hiddenInputs');
  const payBtn   = document.getElementById('btnPay');
  const typeBtns = document.querySelectorAll('.tt-btn');

  const qrModalEl = document.getElementById('qrModal');
  const countdownEl = document.getElementById('countdown');
  const modalAmountEl = document.getElementById('modalAmount');
  const btnConfirmPay = document.getElementById('btnConfirmPay');
  const btnCancelPay = document.getElementById('btnCancelPay');

  let selected = []; // {id, base, label}
  let timer = null;
  let remain = 30;

  function getCoef(type){
    const btn = document.querySelector(`.tt-btn[data-type="${type}"]`);
    return btn ? Number(btn.dataset.coef) : 1.0;
  }

  function formatVND(n){ return new Intl.NumberFormat('vi-VN').format(n) + ' đ'; }

  function calcTotal(){
    return selected.reduce((s,x)=> s + Math.round(Number(x.base) * coef), 0);
  }

  function render(){
    if(selected.length === 0){
      listBox.textContent = '—';
      payBtn.disabled = true;
      payBtn.textContent = 'Thanh toán';
    }else{
      listBox.textContent = selected.map(x => x.label).join(', ');
      const t = calcTotal();
      totalTxt.textContent = formatVND(t);
      payBtn.disabled = false;
      payBtn.textContent = 'Thanh toán ' + formatVND(t);
    }

    // inputs ẩn
    hidden.innerHTML = '';
    selected.forEach(x => {
      const i = document.createElement('input');
      i.type='hidden'; i.name='seat_ids[]'; i.value=x.id;
      hidden.appendChild(i);
    });
    document.getElementById('ticket_type').value = ticketType;
    // cập nhật tổng (dòng trên)
    totalTxt.textContent = formatVND(calcTotal());
  }

  // chọn ghế
  seatBtns.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id   = Number(btn.dataset.id);
      const base = Number(btn.dataset.base);
      const label = btn.title.split(' • ')[0];
      btn.classList.toggle('selected');
      if(btn.classList.contains('selected')){
        selected.push({id, base, label});
      }else{
        selected = selected.filter(x => x.id !== id);
      }
      render();
    });
  });

  // loại vé
  typeBtns.forEach(b=>{
    b.addEventListener('click', ()=>{
      typeBtns.forEach(x=>x.classList.remove('active'));
      b.classList.add('active');
      ticketType = b.dataset.type;
      coef = Number(b.dataset.coef);
      render();
    });
  });

  // mở modal thanh toán
  payBtn.addEventListener('click', ()=>{
    if(selected.length === 0){ return; }
    // hiển thị số tiền trong modal
    modalAmountEl.textContent = formatVND(calcTotal());
    // reset countdown
    remain = 30; countdownEl.textContent = remain;

    // init bootstrap modal
    if(!bsModal){ bsModal = new bootstrap.Modal(qrModalEl); }
    bsModal.show();

    // start timer
    timer && clearInterval(timer);
    timer = setInterval(()=>{
      remain--;
      countdownEl.textContent = remain;
      if(remain <= 0){
        clearInterval(timer);
        bsModal.hide(); // tự động đóng
      }
    }, 1000);
  });

  // hủy -> chỉ đóng modal, KHÔNG submit
  btnCancelPay.addEventListener('click', ()=>{
    timer && clearInterval(timer);
  });

  // xác nhận -> gắn pay_now=1 rồi submit form
  btnConfirmPay.addEventListener('click', ()=>{
    timer && clearInterval(timer);
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'pay_now'; inp.value = '1';
    document.getElementById('seatForm').appendChild(inp);
    document.getElementById('seatForm').submit();
  });

  render();
})();
</script>
@endsection
