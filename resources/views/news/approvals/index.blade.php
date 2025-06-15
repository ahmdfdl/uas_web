@extends('layouts.app')
@section('title', 'Approval Berita')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1 class="h3 text-gray-800">Approval Berita</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Penulis</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendingNews as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->author->name ?? '-' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>
                    <!-- Tombol Approve -->
                    <form action="{{ route('news.approvals.approve', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>

                    <!-- Tombol Reject -->
                    <form action="{{ route('news.approvals.reject', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="text" name="notes" placeholder="Alasan" required class="form-control form-control-sm d-inline w-50">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
