@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div style="padding: 40px 20px; max-width: 1000px; margin: auto;" id="pengguna-container">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 2rem; color: #111827; margin-bottom: 8px;">Kelola Data Pengguna</h1>
        <p style="color: #6b7280;">Tambah, ubah, dan hapus akun pengguna serta atur hak akses rolenya.</p>
    </div>

    <div id="alert-area"></div>

    <!-- FORM TAMBAH / UBAH PENGGUNA -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 35px;">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">Form Pengguna</h2>
        <form id="form-pengguna" novalidate style="display: grid; gap: 15px;">
            <input type="hidden" id="user-id">
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Nama Lengkap:</label>
                <input type="text" id="user-name" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small style="color: red; font-size: 13px;" id="err-name"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Email:</label>
                <input type="email" id="user-email" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small style="color: red; font-size: 13px;" id="err-email"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Password:</label>
                <input type="password" id="user-password" placeholder="Kosongkan jika tidak ingin mengubah password" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <small style="color: red; font-size: 13px;" id="err-password"></small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #4b5563;">Role / Hak Akses:</label>
                <select id="user-role" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: white;">
                    <option value="Admin">Admin</option>
                    <option value="Petugas">Petugas</option>
                    <option value="Siswa">Siswa</option>
                </select>
                <small style="color: red; font-size: 13px;" id="err-role"></small>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Simpan Pengguna</button>
                <button type="button" id="btn-batal" style="display:none; background: #9ca3af; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">Batal Ubah</button>
            </div>
        </form>
    </div>

    <!-- TABEL PENGGUNA -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="font-size: 1.25rem; color: #374151; margin-bottom: 20px;">Daftar Pengguna Sistem</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; color: #374151;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Nama</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Email</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb;">Role</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-pengguna">
                    <tr><td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const API = '/pengguna';
    const FIELD = ['name', 'email', 'password', 'role'];
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
            pesan('Validasi gagal. Periksa kembali form.', 'red');
        } else {
            pesan(err.message, 'red');
        }
    }

    async function muatPengguna() {
        try {
            const json = await apiRequest('');
            const dataUser = Array.isArray(json) ? json : (json.data || []);
            
            $('tbody-pengguna').innerHTML = dataUser.map((u) => {
                const roleName = u.roles?.[0]?.name || 'Siswa';
                return `
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 500;">${esc(u.name)}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">${esc(u.email)}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;"><span style="background: #e0e7ff; color: #4f46e5; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">${esc(roleName)}</span></td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">
                            <button data-aksi="ubah" data-id="${u.id}" style="background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 4px;">Ubah</button>
                            <button data-aksi="hapus" data-id="${u.id}" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('') || '<tr><td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data pengguna.</td></tr>';
        } catch (err) {
            $('tbody-pengguna').innerHTML = `<tr><td colspan="4" style="color:red; text-align: center; padding: 20px;">Gagal memuat data.</td></tr>`;
        }
    }

    function isiForm(user = {}) {
        FIELD.forEach((f) => { if($('err-' + f)) $('err-' + f).textContent = ''; });
        $('user-id').value = user.id ?? '';
        $('user-name').value = user.name ?? '';
        $('user-email').value = user.email ?? '';
        $('user-password').value = '';
        $('user-role').value = user.role ?? 'Siswa';
        $('btn-batal').style.display = $('user-id').value ? 'inline-block' : 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function simpan(e) {
        e.preventDefault();
        const id = $('user-id').value;
        const body = {
            name: $('user-name').value.trim(),
            email: $('user-email').value.trim(),
            password: $('user-password').value,
            role: $('user-role').value,
        };
        if (!body.password && id) delete body.password; // Jangan kirim password kosong saat edit

        try {
            await apiRequest(id ? `/${id}` : '', { method: id ? 'PUT' : 'POST', body });
            pesan(id ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.', 'green');
            isiForm();
            await muatPengguna();
        } catch (err) { tanganiError(err); }
    }

    async function hapus(id) {
        if (!confirm('Yakin ingin menghapus pengguna ini?')) return;
        try {
            await apiRequest(`/${id}`, { method: 'DELETE' });
            pesan('Pengguna berhasil dihapus.', 'green');
            await muatPengguna();
        } catch (err) { tanganiError(err); }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        $('form-pengguna').onsubmit = simpan;
        $('btn-batal').onclick = () => isiForm();

        $('tbody-pengguna').onclick = async (e) => {
            const b = e.target.closest('button[data-aksi]');
            if (!b) return;
            
            if (b.dataset.aksi === 'hapus') {
                hapus(b.dataset.id);
            } else if (b.dataset.aksi === 'ubah') {
                try {
                    const detail = await apiRequest(`/${b.dataset.id}`);
                    isiForm(detail);
                } catch(err) { tanganiError(err); }
            }
        };

        await muatPengguna();
    });
</script>
@endpush