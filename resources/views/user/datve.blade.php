<h2>Đặt vé xem phim</h2>

<form action="{{ route('datve.store') }}" method="POST">
    @csrf
    <p>Chọn ghế: <strong>A5</strong></p>
    <p>Tổng tiền: <strong>120,000 VND</strong></p>
    <button type="submit" class="btn btn-primary">Xác nhận đặt vé</button>
</form>
