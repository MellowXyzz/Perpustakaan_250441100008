@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;" data-can-kelola="{{ auth()->check() && auth()->user()->can('kelola data buku') ? '1' : '0' }}" id="bukan-container-utama">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Daftar Koleksi Buku</h1>
        <p style="color: #6b7280;">Kelola dan jelajahi seluruh data buku perpustakaan dengan mudah.</p>
    </div>

    <div id="alert-area"></div>

    <!-- BLOK FORM: Hanya muncul jika punya izin kelola data buku -->
    @can('kelola data buku')
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 35px;">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">Form Data Buku</h2>
        <form id="form-buku" novalidate style="display: grid; gap: 15px;">
            <input type="hidden" id="buku-id">
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Kategori ID (Angka):</label>
                <input type="number" id="buku-kategori_id" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small id="err-kategori_id" style="color: red; font-size: 13px;"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Judul Buku:</label>
                <input type="text" id="buku-judul" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small id="err-judul" style="color: red; font-size: 13px;"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Nama Penulis:</label>
                <input type="text" id="buku-penulis" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small id="err-penulis" style="color: red; font-size: 13px;"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Tahun Terbit:</label>
                <input type="number" id="buku-tahun_terbit" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small id="err-tahun_terbit" style="color: red; font-size: 13px;"></small>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Simpan Data</button>
                <button type="button" id="btn-batal" style="display:none; background: #9ca3af; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Batal Ubah</button>
            </div>
        </form>
    </div>
    @endcan

    <!-- TABEL DATA BUKU -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Tabel Data Buku</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Kategori</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Judul</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Penulis</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Tahun Terbit</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-buku">
                    @foreach(\App\Models\Buku::all() as $b)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $b->kategori_id }}</td>
                            
                            <!-- INI BAGIAN YANG DIUBAH UNTUK SOAL A1 -->
                            <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">{!! $b->judul !!}</td>
                            
                            <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $b->penulis }}</td>
                            <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $b->tahun_terbit }}</td>
                            <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">
                                <button data-aksi="detail" data-id="{{ $b->id }}" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Detail</button>
                                @can('kelola data buku')
                                    <button data-aksi="ubah" data-id="{{ $b->id }}" style="background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Ubah</button>
                                    <button data-aksi="hapus" data-id="{{ $b->id }}" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const canKelolaBuku = document.getElementById('bukan-container-utama').getAttribute('data-can-kelola') === '1';
    const API = '/buku'; 
    const FIELD = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];
    const $ = (id) => document.getElementById(id);
    const esc = (t) => String(t ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    const pesan = (teks, warna = 'green') => {
        const bg = warna === 'green' ? '#ecfdf5' : '#fef2f2';
        const border = warna === 'green' ? '#10b981' : '#ef4444';
        const textCol = warna === 'green' ? '#047857' : '#b91c1c';
        $('alert-area').innerHTML = `<div style="color:${textCol}; padding:12px; border:1px solid ${border}; background-color:${bg}; border-radius: 6px; margin-bottom: 15px; font-weight: 500;">${esc(teks)}</div>`;
        setTimeout(() => $('alert-area').innerHTML = '', 5000);
    };

    async function apiRequest(path, { method = 'GET', body = null } = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken };
        if (body) headers['Content-Type'] = 'application/json';
        
        const res = await fetch(API + path, { method, headers, body: body ? JSON.stringify(body) : undefined });
        if (res.status === 204) return null;
        
        const data = await res.json().catch(() => null);
        if (!res.ok) throw { status: res.status, message: data?.message ?? 'Gagal', errors: data?.errors ?? {} };
        return data;
    }

    function tanganiError(err) {
        FIELD.forEach((f) => { if($('err-' + f)) $('err-' + f).textContent = ''; });
        if (err.status === 422) {
            Object.entries(err.errors).forEach(([f, m]) => { if ($('err-' + f)) $('err-' + f).textContent = m.join(' '); });
            pesan('Validasi gagal.', 'red');
        } else {
            pesan(err.message, 'red');
        }
    }

    async function muatBuku() {
        try {
            const json = await apiRequest('');
            const dataBuku = Array.isArray(json) ? json : (json.data || []);
            
            const aksi = (id) => {
                let tombol = `<button data-aksi="detail" data-id="${id}" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Detail</button>`;
                if (canKelolaBuku) {
                    tombol += ` <button data-aksi="ubah" data-id="${id}" style="background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Ubah</button> 
                                <button data-aksi="hapus" data-id="${id}" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Hapus</button>`;
                }
                return tombol;
            };
            
            $('tbody-buku').innerHTML = dataBuku.map((b) => `
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px; border: 1px solid #e5e7eb;">${esc(b.kategori_buku ?? b.kategori_id)}</td>
                    <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">${b.judul_buku ?? b.judul}</td>
                    <td style="padding: 12px; border: 1px solid #e5e7eb;">${esc(b.nama_penulis ?? b.penulis)}</td>
                    <td style="padding: 12px; border: 1px solid #e5e7eb;">${b.tahun ?? b.tahun_terbit}</td>
                    <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">${aksi(b.id_buku ?? b.id)}</td>
                </tr>
            `).join('') || '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data buku.</td></tr>';
        } catch (err) {
            $('tbody-buku').innerHTML = `<tr><td colspan="5" style="color:red; text-align: center; padding: 20px;">Gagal memuat data.</td></tr>`;
        }
    }

    function isiForm(buku = {}) {
        FIELD.forEach((f) => { if($('err-' + f)) $('err-' + f).textContent = ''; });
        $('buku-id').value = buku.id ?? buku.id_buku ?? ''; 
        $('buku-kategori_id').value = buku.kategori_id ?? buku.kategori_buku ?? '';
        $('buku-judul').value = buku.judul ?? buku.judul_buku ?? ''; 
        $('buku-penulis').value = buku.penulis ?? buku.nama_penulis ?? '';
        $('buku-tahun_terbit').value = buku.tahun_terbit ?? buku.tahun ?? '';
        if ($('btn-batal')) $('btn-batal').style.display = $('buku-id').value ? 'inline-block' : 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function simpan(e) {
        e.preventDefault();
        const id = $('buku-id').value;
        const body = {
            kategori_id: $('buku-kategori_id').value || null,
            judul: $('buku-judul').value.trim(),
            penulis: $('buku-penulis').value.trim(),
            tahun_terbit: $('buku-tahun_terbit').value || null,
        };

        try {
            await apiRequest(id ? `/${id}` : '', { method: id ? 'PUT' : 'POST', body });
            pesan(id ? 'Data buku berhasil diperbarui.' : 'Data buku berhasil ditambahkan.', 'green');
            isiForm(); 
            //await muatBuku();
        } catch (err) { tanganiError(err); }
    }

    async function hapus(id) {
        if (!confirm('Yakin ingin menghapus data buku ini?')) return;
        try {
            await apiRequest(`/${id}`, { method: 'DELETE' });
            pesan('Data buku berhasil dihapus.', 'green');
            //await muatBuku();
        } catch (err) { tanganiError(err); }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        if ($('form-buku')) $('form-buku').onsubmit = simpan;
        if ($('btn-batal')) $('btn-batal').onclick = () => isiForm();

        $('tbody-buku').onclick = async (e) => {
            const b = e.target.closest('button[data-aksi]');
            if (!b) return;
            
            if (b.dataset.aksi === 'hapus') {
                hapus(b.dataset.id);
            } else if (b.dataset.aksi === 'detail') {
                window.location.href = `/buku/${b.dataset.id}`;
            } else if (b.dataset.aksi === 'ubah') {
                try {
                    const detail = await apiRequest(`/${b.dataset.id}`);
                    isiForm(detail.data || detail); 
                } catch(err) { tanganiError(err); }
            }
        };

        //await muatBuku();
    });
</script>
@endpush