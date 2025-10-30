<div class="page-toolbar">
  <div class="container d-flex flex-wrap gap-2">
    <a href="{{ route('admin.movies.index') }}" class="pt-btn">Quản lý phim</a>
    <a href="{{ route('admin.showtimes.index') }}" class="pt-btn">Quản lý suất chiếu</a>
    <a href="{{ route('admin.rooms.index') }}" class="pt-btn">Quản lý phòng chiếu</a>
    <a href="{{ route('admin.prices.index') }}" class="pt-btn">Quản lý giá vé</a>
    <a href="{{ route('admin.tickets.index') }}" class="pt-btn">Quản lý đơn vé</a>
    <a href="{{ route('admin.reports.index') }}" class="pt-btn">Báo cáo & thống kê</a>
    {{-- thêm nếu cần: Quản lý khách hàng, nhân viên... --}}
  </div>
</div>
