@extends('layouts.app')
@section('title', 'Edit Berita')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Berita</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" class="form-control" name="title" value="{{ $news->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <input type="text" class="form-control" name="excerpt" value="{{ $news->excerpt }}" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Isi Berita</label>
                        <textarea class="form-control" name="content" rows="6" required>{{ $news->content }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select class="form-control" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $news->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" class="form-control-file" name="thumbnail">
                        @if($news->thumbnail)
                            <img src="{{ asset('storage/news/' . $news->thumbnail) }}" width="80" class="mt-2">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
