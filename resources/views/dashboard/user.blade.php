{{-- resources/views/user/dashboard/user.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Dashboard User</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <p>Selamat datang, {{ Auth::user()->name }}! Kamu login sebagai <strong>User</strong>.</p>

            <ul>
                <li><a href="{{ route('users.profile') }}">Edit Profil</a></li>
                <li><a href="{{ route('news.index') }}">Lihat Berita</a></li>
                <li><a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection
