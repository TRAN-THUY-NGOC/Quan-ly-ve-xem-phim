@if ($paginator->hasPages())
    <style>
        /* style nhẹ, bạn có thể chuyển vào file CSS chung nếu muốn */
        .custom-pagination { text-align:center; margin-top:18px; }
        .custom-pagination ul { display:inline-flex; list-style:none; padding:0; margin:0; gap:6px; align-items:center; flex-wrap:wrap; }
        .custom-pagination li { }
        .custom-pagination a,
        .custom-pagination span {
            display:inline-block;
            padding:6px 9px;
            border-radius:6px;
            border:1px solid #ddd;
            text-decoration:none;
            min-width:32px;
            text-align:center;
            background:#f7f7f7;
            color:#333;
            font-weight:600;
        }
        .custom-pagination a:hover { background:#d82323; color:#fff; border-color:#d82323; }
        .custom-pagination .active { background:#d82323; color:#fff; border:1px solid #d82323; }
        .custom-pagination .disabled { color:#aaa; background:#fff; border:1px solid #eee; cursor:default; }
        .pagination-info { margin-top:8px; color:#555; font-size:14px; }

        /* responsive: ẩn bớt các số trang ở giữa khi nhỏ */
        @media (max-width:600px){
            .custom-pagination ul li:not(.active):not(.disabled):nth-child(n+4):nth-last-child(n+4){ display:none; }
            .custom-pagination ul li:first-child,
            .custom-pagination ul li:last-child { display:inline-block; }
        }
    </style>

    <div class="custom-pagination">
        <nav>
            <ul>
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <li><span class="disabled">&lsaquo;</span></li>
                @else
                    <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
                @endif

                {{-- Elements (Laravel đã tạo '...' tự động nếu cần) --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" --}}
                    @if (is_string($element))
                        <li><span class="disabled">{{ $element }}</span></li>
                    @endif

                    {{-- Array of links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><span class="active">{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
                @else
                    <li><span class="disabled">&rsaquo;</span></li>
                @endif
            </ul>
        </nav>

        <div class="pagination-info">
            Trang <strong>{{ $paginator->currentPage() }}</strong> / {{ $paginator->lastPage() }}
            – Tổng <strong>{{ $paginator->total() }}</strong> mục
        </div>
    </div>
@endif
