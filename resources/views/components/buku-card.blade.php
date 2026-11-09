<div class="book-card">

    <div class="book-content">

        <span class="book-category">
            {{ $buku->kategori->nama }}
        </span>

        <h3>
            {{ $buku->judul }}
        </h3>

        <p class="book-author">
            {{ $buku->penulis }}
        </p>

        <div class="book-year">
            <span>Terbit</span>
            {{ $buku->tahun_terbit }}
        </div>

        <div class="book-action">

            {{ $detail }}

        </div>

    </div>

</div>