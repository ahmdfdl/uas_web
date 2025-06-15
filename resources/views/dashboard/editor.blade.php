@extends('layouts.app')
@section('title', 'Dashboard Editor')
@section('content')
<div class="container py-4">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-pill px-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-pill px-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h2 class="mb-3 text-primary">Dashboard Editor</h2>
    <p class="mb-4">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda login sebagai <strong>Editor</strong>.</p>

    <div class="row g-4">
        <!-- Pending News -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-info bg-opacity-75 text-white rounded-pill px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold text-uppercase">Menunggu Persetujuan</h6>
                        <h3 class="mb-0">{{ $pendingNews->count() }} Berita</h3>
                    </div>
                    <i class="bi bi-clock-history fs-1 text-white-50"></i>
                </div>
            </div>
        </div>

        <!-- Approved News -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-success bg-opacity-75 text-white rounded-pill px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold text-uppercase">Disetujui</h6>
                        <h3 class="mb-0">{{ \App\Models\News::where('status', 'published')->count() }} Berita</h3>
                    </div>
                    <i class="bi bi-check2-circle fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card mt-5 border-0 shadow rounded-4">
        <div class="card-header bg-light rounded-top-4 py-3 px-4">
            <h5 class="mb-0 text-primary fw-semibold">Berita Menunggu Persetujuan</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light rounded-top">
                    <tr>
                        <th>Judul</th>
                        <th>Wartawan</th>
                        <th>Kategori</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingNews as $news)
                        <tr>
                            <td>{{ $news->title }}</td>
                            <td>{{ $news->author->name }}</td>
                            <td>{{ $news->category->name }}</td>
                            <td class="text-end">
                                <form action="{{ route('news.approvals.approve', $news->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-success rounded-pill btn-sm me-1"
                                            onclick="return confirm('Approve berita ini?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('news.approvals.reject', $news->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="notes" value="Berita tidak sesuai ketentuan">
                                    <button class="btn btn-outline-danger rounded-pill btn-sm"
                                            onclick="return confirm('Reject berita ini?')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Tidak ada berita menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
