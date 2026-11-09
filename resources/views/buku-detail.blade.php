@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

<section class="detail-page">

    <a href="{{ route('buku.index') }}" class="back-button">
        ← Kembali ke Daftar Buku
    </a>


    @if ($buku)

        <div class="detail-container">


            <div class="detail-information">

                <span class="page-label">
                    DETAIL BUKU
                </span>

                <h1>
                    {{ $buku['judul'] }}
                </h1>

                <p class="detail-author">
                    Ditulis oleh
                    <strong>{{ $buku['penulis'] }}</strong>
                </p>


                <div class="detail-list">

                    <div>
                        <span>ID Buku</span>
                        <strong>{{ $buku['id'] }}</strong>
                    </div>

                    <div>
                        <span>Tahun Terbit</span>
                        <strong>{{ $buku->tahun_terbit }}</strong>
                    </div>

                    <div>
                        <span>Kategori</span>
                        <strong>{{ $buku->kategori->nama}}</strong>
                    </div>

                </div>


                <p class="detail-description">
                    Buku ini merupakan bagian dari koleksi
                    Perpustakaan Digital dan dapat digunakan
                    sebagai sumber bacaan dan pengetahuan.
                </p>


                <a
                    href="{{ route('buku.index') }}"
                    class="hero-button"
                >
                    ← Kembali
                </a>

            </div>

        </div>

    @else

        <div class="empty-book">

            <h2>
                Buku Tidak Ditemukan
            </h2>

            <p>
                Data buku yang kamu cari tidak tersedia.
            </p>

            <a
                href="{{ route('buku.index') }}"
                class="hero-button"
            >
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

</section>

@endsection