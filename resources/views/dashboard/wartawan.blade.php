@extends('layouts.app')

@section('title', 'Dashboard Wartawan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Welcome Card -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3 class="h4 text-gray-800 mb-3">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="mb-0">Kamu login sebagai <strong>Wartawan</strong>. Silakan pilih menu berikut untuk mengelola berita atau akunmu.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('news.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-pen"></i> Tulis Berita Baru
                        </a>
                        <a href="{{ route('news.index') }}" class="btn btn-info btn-block">
                            <i class="fas fa-list"></i> Daftar Berita Saya
                        </a>
                        <a href="{{ route('users.profile') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-user-cog"></i> Edit Profil
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                           class="btn btn-danger btn-block">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
