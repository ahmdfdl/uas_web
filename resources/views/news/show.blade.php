@extends('layouts.app')
@section('title', $news->title)
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Berita</h6>
            </div>
            <div class="card-body">
                <h2>{{ $news->title }}</h2>
                <p><strong>Kategori:</strong> {{ $news->category->name ?? '-' }}</p>
                <p><strong>Penulis:</strong> {{ $news->author->name ?? '-' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($news->status) }}</p>
                @if($news->thumbnail)
                    <img src="{{ asset('storage/news/' . $news->thumbnail) }}" width="120" class="mb-3">
                @endif
                <div class="mb-3">{!! nl2br(e($news->content)) !!}</div>
                <a href="{{ route('news.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
