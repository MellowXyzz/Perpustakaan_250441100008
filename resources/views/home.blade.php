@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<section class="hero">
    <div class="hero-container">
        <div class="hero-text">

            <h1>
                Selamat Datang di
                <span>PerpustakaanKu</span>
            </h1>

            <p class="hero-description">
                Temukan berbagai koleksi buku untuk menambah
                pengetahuan, wawasan, dan inspirasi.
            </p>

            <p class="hero-info">
                Jelajahi koleksi buku kami dengan mudah dan nyaman.
            </p>

            @auth
                <!-- Jika sudah login, langsung ke halaman buku -->
                <a href="{{ route('buku.index') }}" class="hero-button">
                    Jelajahi Buku
                    <span>→</span>
                </a>
            @else
                <!-- Jika belum login, arahkan ke halaman login -->
                <a href="{{ route('login') }}" class="hero-button">
                    Login untuk Jelajahi
                    <span>→</span>
                </a>
            @endauth

        </div>
    </div>
</section>

@endsection