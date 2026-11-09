@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;" id="peminjaman-container">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Kelola Data Peminjaman</h1>
        <p style="color: #6b7280;">Pantau daftar buku yang sedang dipinjam oleh pengguna serta kelola status pengembaliannya.</p>
    </div>

    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="font-size: 1.25rem; color: #374151; margin: 0;">Daftar Transaksi Peminjaman</h2>
    <a href="{{ route(auth()->user()->hasRole('Petugas') ? 'petugas.peminjaman.create' : 'admin.peminjaman.create') }}" style="background: #4f46e5; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
        + Tambah Peminjaman
    </a>
</div>

    <div id="alert-area"></div>

    <!-- TABEL PEMINJAMAN -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Daftar Transaksi Peminjaman</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Judul Buku</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Tanggal Pinjam</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Status</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-peminjaman">
                    <tr><td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const API = '/admin/peminjaman';
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
        if (!res.ok) throw { status: res.status, message: data?.message ?? 'Gagal' };
        return data;
    }

    async function muatPeminjaman() {
        try {
            const json = await apiRequest('');
            const dataPinjam = Array.isArray(json) ? json : (json.data || []);
            
            $('tbody-peminjaman').innerHTML = dataPinjam.map((p) => {
                const namaUser = p.anggota?.nama ?? p.anggota?.nama_anggota ?? 'Tidak diketahui';
                const judulBuku = p.buku?.judul_buku ?? p.buku?.judul ?? 'Tidak diketahui';
                const tglPinjam = p.tanggal_pinjam ?? p.created_at ?? '-';
                const status = p.status ?? 'Dipinjam';

                return `
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">${esc(namaUser)}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">${esc(judulBuku)}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">${esc(tglPinjam)}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">
                            <span style="background: ${status === 'Dikembalikan' ? '#ecfdf5' : '#fef3c7'}; color: ${status === 'Dikembalikan' ? '#047857' : '#d97706'}; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">
                                ${esc(status)}
                            </span>
                        </td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">
                            ${status !== 'Dikembalikan' ? `<button data-aksi="selesai" data-id="${p.id ?? p.id_peminjaman}" style="background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Selesaikan</button>` : ''}
                            <button data-aksi="hapus" data-id="${p.id ?? p.id_peminjaman}" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('') || '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data peminjaman.</td></tr>';
        } catch (err) {
            $('tbody-peminjaman').innerHTML = `<tr><td colspan="5" style="color:red; text-align: center; padding: 20px;">Gagal memuat data peminjaman.</td></tr>`;
        }
    }

    async function updateStatus(id) {
        try {
            await apiRequest(`/${id}`, { method: 'PUT', body: { status: 'Dikembalikan' } });
            pesan('Status peminjaman berhasil diperbarui menjadi Dikembalikan.', 'green');
            await muatPeminjaman();
        } catch (err) { pesan('Gagal memperbarui status.', 'red'); }
    }

    async function hapus(id) {
        if (!confirm('Yakin ingin menghapus riwayat peminjaman ini?')) return;
        try {
            await apiRequest(`/${id}`, { method: 'DELETE' });
            pesan('Riwayat peminjaman berhasil dihapus.', 'green');
            await muatPeminjaman();
        } catch (err) { pesan('Gagal menghapus data.', 'red'); }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        $('tbody-peminjaman').onclick = async (e) => {
            const b = e.target.closest('button[data-aksi]');
            if (!b) return;
            
            if (b.dataset.aksi === 'hapus') {
                hapus(b.dataset.id);
            } else if (b.dataset.aksi === 'selesai') {
                updateStatus(b.dataset.id);
            }
        };

        await muatPeminjaman();
    });
</script>
@endpush