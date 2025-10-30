@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3>Danh sách phim</h3>
  <ul>
    @foreach($movies as $m)
      <li>
        <a href="{{ route('movies.show', $m) }}">{{ $m->title }}</a>
        — {{ $m->genre }} — {{ $m->duration_min }}p
      </li>
    @endforeach
  </ul>
  {{ $movies->links() }}
</div>
@endsection
