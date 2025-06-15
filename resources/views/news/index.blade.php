@extends('layouts.app')
@section('title', 'Daftar Berita')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Daftar Berita</h1>
    <a href="{{ route('news.create') }}" class="btn btn-primary">Tambah Berita</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Penulis</th>
            <th>Thumbnail</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->author->name ?? '-' }}</td>
                <td>
                    @if($item->thumbnail)
                        <img src="{{ asset('storage/news/' . $item->thumbnail) }}" width="60">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('news.show', $item->id) }}" class="btn btn-info btn-sm">Lihat</a>

                    {{-- Penulis atau Admin --}}
                    @if(auth()->id() == $item->author_id || auth()->user()->role === 'admin')
                        @if($item->status == 'draft')
                            <form action="{{ route('news.submit', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Ajukan ke Editor</button>
                            </form>
                        @endif

                        <a href="{{ route('news.edit', $item->id) }}" class="btn btn-secondary btn-sm">Edit</a>

                        <form action="{{ route('news.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus berita?')">Hapus</button>
                        </form>
                    @endif

                    {{-- Editor: Approve / Reject --}}
                    @if(auth()->user()->role === 'editor' && $item->status === 'pending')
                        <form action="{{ route('news.approvals.approve', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>

                        <form action="{{ route('news.approvals.reject', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
