@extends('layouts.app_staf-prodi', ['activeMenu' => 'data_mitra_dan_penilaian', 'title' => 'Dashboard Staf Prodi'])
@section('content')
    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Data Mitra</h1>

            <button type="button" onclick="openTambahMitraModal()"
                class="flex items-center gap-x-2 px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Mitra
            </button>
        </div>

        <form method="GET" action="{{ route('staf-prodi.data_mitra_dan_penilaian') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa, NPM, atau mitra..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Tombol Cari Ikon (Sesuai Gambar) --}}
                <button type="submit"
                    class="p-2.5 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-[#690dac]">
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tl-xl w-[18%]">
                                Nama Mahasiswa</th>
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider w-[10%]">
                                NPM</th>
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider w-[20%]">
                                Mitra</th>
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider w-[12%]">
                                Periode</th>
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider w-[17%]">
                                Posisi di Perusahaan</th>
                            <th
                                class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider w-[13%]">
                                Status Penilaian</th>
                            <th
                                class="text-white text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tr-xl w-[10%]">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dataList as $data)
                            @php
                                $periode = $data->periode_magang ?? '-';

                                $hasPenilaian = $data->penilaian && $data->penilaian->terisi;
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ optional($data->mahasiswa)->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data->mahasiswa->npm ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($data->mitra)->nama_perusahaan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $periode }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data->posisi_dilamar ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $hasPenilaian ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $hasPenilaian ? 'Terisi' : 'Belum Terisi' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap table-cell aksi text-sm font-medium">
                                    <div class="flex items-center justify-center">
                                        <button onclick="openDetailModal('{{ $data->id_pengajuan }}')" title="Lihat Detail"
                                            class="text-blue-500 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data mahasiswa magang yang disetujui
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
                    {{-- Tampilan Mobile --}}
                    @if ($dataList->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $dataList->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($dataList->hasMorePages())
                        <a href="{{ $dataList->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                {{-- Tampilan Desktop --}}
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        {{-- Info Data (Warna Abu-abu sesuai gambar) --}}
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $dataList->firstItem() ?? 0 }} sampai {{ $dataList->lastItem() ?? 0 }} dari total
                            {{ $dataList->total() }} data
                        </p>
                    </div>

                    <div>
                        {{-- Navigasi --}}
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            {{-- Tombol Sebelumnya --}}
                            @if ($dataList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $dataList->previousPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            {{-- Kotak Angka Halaman --}}
                            <div
                                class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $dataList->currentPage() }}
                            </div>

                            {{-- Tombol Berikutnya --}}
                            @if ($dataList->hasMorePages())
                                <a href="{{ $dataList->nextPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL DETAIL --}}
        <div id="detailPenilaianModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative animate-fade-in max-h-[90vh] overflow-y-auto">
                <div
                    class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between sticky top-0 z-10">
                    <h2 class="text-base font-bold">Detail Penilaian Mahasiswa</h2>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="mb-5 border-b pb-4 border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi Mahasiswa</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Nama Mahasiswa</p>
                                <p id="detailNama" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">NPM</p>
                                <p id="detailNpm" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Perusahaan Mitra</p>
                                <p id="detailMitra" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Periode Magang</p>
                                <p id="detailPeriode" class="font-medium text-gray-900">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-600 mb-0.5">Posisi di Perusahaan</p>
                                <p id="detailPosisi" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div id="filePenilaianSection" class="mb-5 border-b pb-4 border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">File Penilaian dari Mitra</h3>
                        <div class="bg-blue-50 p-4 rounded-lg" id="filePenilaianContainer"></div>
                    </div>
                    <div id="nilaiAkhirSection">
                        <div class="p-4 border border-gray-200 rounded-lg mb-4 bg-white shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-gray-900">Nilai Mitra</h3>
                                <button id="editNilaiBtn" onclick="toggleEditNilai()"
                                    class="text-[#155dfc] hover:bg-[#eff6ff] transition duration-150 p-1.5 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                                <div id="viewNilai">
                                    <span id="displayNilaiHuruf" class="text-3xl font-bold">A</span>
                                    <span id="displayNilaiAngka" class="text-xl font-bold ml-2">(100)</span>
                                </div>
                                <div id="editNilai" style="display:none">
                                    
                                    {{-- BLOK NILAI ANGKA --}}
                                    <div class="mb-4 border border-gray-200 rounded-lg p-3 bg-white">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                                            Nilai Angka
                                        </label>
                                        <input
                                            id="inputNilaiAngka"
                                            type="number"
                                            min="0"
                                            max="100"
                                            placeholder="0 – 100"
                                            oninput="autoSetNilaiHuruf()"
                                            class="w-full text-center text-lg border border-gray-300 rounded-md py-2 focus:ring-2 focus:ring-purple-300"
                                        >
                                    </div>
                                    {{-- BLOK NILAI HURUF --}}
                                    <div class="mb-3 border border-gray-200 rounded-lg p-3 bg-gray-50">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                                            Nilai Huruf
                                        </label>
                                        <input
                                            id="inputNilaiAkhir"
                                            type="text"
                                            maxlength="3"
                                            placeholder="A/B/C/D/E"
                                            class="w-full text-center text-lg font-bold uppercase
                                                border border-gray-300 rounded-md py-2"
                                        />
                                    </div>

                                    <button onclick="simpanNilai()"
                                        class="w-full bg-purple-600 text-white py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                                        Simpan Nilai
                                    </button>
                                </div>
                        </div>
                        
                            <div class="p-4 bg-yellow-50 rounded-lg shadow-sm">
                                <p class="text-sm text-yellow-800">
                                    <span class="font-semibold">Catatan:</span> Data penilaian dari mitra bersifat
                                    read-only.
                                    Nilai akhir yang Anda tetapkan akan ditampilkan di akun mahasiswa sebagai hasil resmi
                                    kegiatan magang.
                                </p>
                            </div>
                        <div id="belumTerisiNotice" style="display: none;"
                            class="mt-5 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">
                                <span class="font-semibold">Peringatan:</span> Belum ada data penilaian atau file penilaian
                                dari
                                mitra yang tersedia. Nilai Akhir (Prodi) belum dapat ditentukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            </div>

<div id="tambahMitraModal" class="fixed inset-0 z-60 flex items-center justify-center"
    style="display:none; background:rgba(0,0,0,.5)" onclick="closeTambahMitraModal()">

    <div class="bg-white rounded-xl w-full max-w-md p-6 relative" onclick="event.stopPropagation()">

        <!-- Tombol Close -->
        <button onclick="closeTambahMitraModal()"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h2 class="text-lg font-bold mb-4">Tambah Mitra Perusahaan</h2>

        <form id="tambahMitraForm" onsubmit="submitTambahMitra(event)">
            @csrf

            <label class="block text-sm mb-1">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" required
                class="w-full mb-3 border rounded px-3 py-2" placeholder="Masukkan Nama Perusahaan">

            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email_pic" required
                class="w-full mb-4 border rounded px-3 py-2" placeholder="Masukkan Email Perusahaan">

            <button type="submit"
                class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">
                Tambah Mitra
            </button>
        </form>
    </div>
</div>
        </div>

        <style>
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.2s ease-out;
            }
        </style>

 <script>
/* ===============================
    GLOBAL STATE & INITIALIZATION
   ================================ */
let currentIdPengajuan = null;
let hasFilePenilaian = false; // Flag baru untuk kontrol akses edit

document.addEventListener('DOMContentLoaded', function() {

    /* ===============================
        LOGIKA TAMBAH MITRA (FIXED)
       ================================ */
    const tambahMitraForm = document.getElementById('tambahMitraForm');
    if (tambahMitraForm) {
        tambahMitraForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const data = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Memproses...';
            }

            fetch("{{ route('staf-prodi.mitra.tambah') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(async res => {
                const body = await res.json();
                
                if (res.ok) {
                    alert(body.message || 'Mitra berhasil ditambahkan');
                    closeTambahMitraModal();
                    location.reload();
                } else if (res.status === 422) {
                    if (body.errors && body.errors.email_pic) {
                        alert('Email sudah terdaftar. Gunakan email lain.');
                    } else {
                        let errorMsg = Object.values(body.errors).flat().join('\n');
                        alert('Validasi gagal:\n' + errorMsg);
                    }
                } else {
                    alert(body.message || 'Gagal menambahkan mitra');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan pada server.');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Tambah Mitra';
                }
            });
        });
    }

    /* ===============================
        GLOBAL KEYBOARD LISTENER (ESC)
       ================================ */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeTambahMitraModal();
            if (typeof closeVerifikasiMitraModal === "function") {
                closeVerifikasiMitraModal();
            }
        }
    });
});

/* ===============================
    MODAL TAMBAH MITRA 
   ================================ */
function openTambahMitraModal() {
    const modal = document.getElementById('tambahMitraModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeTambahMitraModal() {
    const modal = document.getElementById('tambahMitraModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/* ===============================
    MODAL DETAIL PENILAIAN
   ================================ */
function openDetailModal(idPengajuan) {
    currentIdPengajuan = idPengajuan;
    hasFilePenilaian = false; // Reset flag

    const modal = document.getElementById('detailPenilaianModal');
    if (!modal) return;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    document.getElementById('detailNama').textContent = 'Memuat...';
    document.getElementById('detailNpm').textContent = '-';
    document.getElementById('detailMitra').textContent = '-';
    document.getElementById('detailPeriode').textContent = '-';
    document.getElementById('detailPosisi').textContent = '-';
    document.getElementById('filePenilaianContainer').innerHTML = 'Memuat...';

    fetch(`/data_mitra_dan_penilaian/${idPengajuan}/detail`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert('Gagal memuat data');
                closeDetailModal();
                return;
            }

            const d = res.data;

            document.getElementById('detailNama').textContent = d.nama;
            document.getElementById('detailNpm').textContent = d.npm;
            document.getElementById('detailMitra').textContent = d.mitra;
            document.getElementById('detailPeriode').textContent = d.periode;
            document.getElementById('detailPosisi').textContent = d.posisi;

            const container = document.getElementById('filePenilaianContainer');
            if (d.file_penilaian) {
                hasFilePenilaian = true; // Set true jika file ada
                const filename = d.file_penilaian.split('/').pop();
                container.innerHTML = `
                    <div class="flex justify-between items-center bg-white p-4 border rounded">
                        <div>
                            <p class="font-medium text-sm">${filename}</p>
                            <p class="text-xs text-gray-500">${d.tanggal_upload}</p>
                        </div>
                        <a href="${d.file_penilaian}" target="_blank" class="text-purple-600 font-semibold text-sm">Download</a>
                    </div>`;
            } else {
                hasFilePenilaian = false;
                container.innerHTML = `<p class="text-sm text-gray-500 text-center">File penilaian belum tersedia</p>`;
            }

            document.getElementById('displayNilaiHuruf').textContent = d.nilai_akhir ?? '-';
            document.getElementById('displayNilaiAngka').textContent = d.nilai_angka !== null ? '(' + d.nilai_angka + ')' : '';
            document.getElementById('inputNilaiAkhir').value = d.nilai_akhir ?? '';
            document.getElementById('inputNilaiAngka').value = d.nilai_angka ?? '';
        })
        .catch(err => {
            console.error(err);
            alert('Error server: Gagal memuat detail');
            closeDetailModal();
        });
}

function closeDetailModal() {
    const modal = document.getElementById('detailPenilaianModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/* ===============================
    LOGIKA EDIT & SIMPAN NILAI
   ================================ */
function toggleEditNilai() {
    // Validasi tambahan di sisi klien
    if (!hasFilePenilaian) {
        alert('Maaf, Anda tidak dapat menginput nilai karena file penilaian dari mitra belum tersedia.');
        return;
    }

    const view = document.getElementById('viewNilai');
    const edit = document.getElementById('editNilai');

    if (edit.style.display === 'none') {
        view.style.display = 'none';
        edit.style.display = 'block';
        document.getElementById('inputNilaiAngka').focus();
    } else {
        edit.style.display = 'none';
        view.style.display = 'block';
    }
}

function autoSetNilaiHuruf() {
    const input = document.getElementById('inputNilaiAngka');
    let nilaiAngka = parseInt(input.value, 10);

    if (isNaN(nilaiAngka)) {
        document.getElementById('inputNilaiAkhir').value = '';
        return;
    }

    if (nilaiAngka > 100) nilaiAngka = 100;
    if (nilaiAngka < 0) nilaiAngka = 0;
    input.value = nilaiAngka;

    let huruf = '';
    if (nilaiAngka >= 85) huruf = 'A';
    else if (nilaiAngka >= 70) huruf = 'B';
    else if (nilaiAngka >= 55) huruf = 'C';
    else if (nilaiAngka >= 40) huruf = 'D';
    else huruf = 'E';

    document.getElementById('inputNilaiAkhir').value = huruf;
}

function simpanNilai() {
    const nilaiHuruf = document.getElementById('inputNilaiAkhir').value.trim().toUpperCase();
    const nilaiAngka = parseInt(document.getElementById('inputNilaiAngka').value, 10);

    if (!nilaiHuruf || isNaN(nilaiAngka) || nilaiAngka < 0 || nilaiAngka > 100) {
        alert('Nilai huruf & angka wajib diisi (0-100)');
        return;
    }

    // Menambahkan loading state sederhana pada tombol simpan
    const btnSimpan = document.querySelector('#editNilai button[onclick="simpanNilai()"]');
    if(btnSimpan) btnSimpan.disabled = true;

    fetch(`/data_mitra_dan_penilaian/${currentIdPengajuan}/update-nilai`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nilai_huruf: nilaiHuruf,
            nilai_angka: nilaiAngka
        })
    })
    .then(async res => {
        const data = await res.json();
        
        if (res.ok && data.success) {
            document.getElementById('displayNilaiHuruf').textContent = data.nilai_huruf ?? '-';
            document.getElementById('displayNilaiAngka').textContent = data.nilai_angka !== null ? '(' + data.nilai_angka + ')' : '';
            toggleEditNilai();
            alert('Nilai berhasil disimpan');
        } else {
            // Menampilkan pesan error spesifik dari backend (termasuk validasi file penilaian)
            alert(data.message || 'Gagal menyimpan nilai');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menyimpan nilai (error server)');
    })
    .finally(() => {
        if(btnSimpan) btnSimpan.disabled = false;
    });
}
</script>
@endsection