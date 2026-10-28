@extends('layouts.app_dosen-pembimbing', ['activeMenu' => 'daftar_mahasiswa_bimbingan', 'title' => 'Daftar Mahasiswa Bimbingan'])

@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Mahasiswa Bimbingan</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('dosen-pembimbing.daftar_mahasiswa_bimbingan') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa atau NPM..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Tombol Cari --}}
                <button type="submit"
                    class="p-2.5 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-[#690dac]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">NPM</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Perusahaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status Laporan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($mahasiswaBimbingan as $pengajuan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                {{ $pengajuan->mahasiswa->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $pengajuan->mahasiswa->npm }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $pengajuan->mitra?->nama_perusahaan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $pengajuan->status_badge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $pengajuan->status_laporan_badge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openDetailModal('{{ $pengajuan->id_pengajuan }}')"
                                    class="text-[#155dfc] hover:bg-[#eff6ff] transition duration-150 p-1.5 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada mahasiswa bimbingan saat ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                    {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
                    @if ($mahasiswaBimbingan->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $mahasiswaBimbingan->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($mahasiswaBimbingan->hasMorePages())
                        <a href="{{ $mahasiswaBimbingan->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $mahasiswaBimbingan->firstItem() ?? 0 }} sampai {{ $mahasiswaBimbingan->lastItem() ?? 0 }} dari total {{ $mahasiswaBimbingan->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($mahasiswaBimbingan->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $mahasiswaBimbingan->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $mahasiswaBimbingan->currentPage() }}
                            </div>

                            @if ($mahasiswaBimbingan->hasMorePages())
                                <a href="{{ $mahasiswaBimbingan->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg relative animate-fade-in max-h-[90vh] overflow-y-auto">
            <div
                class="bg-violet-700 text-white px-5 py-3.5 rounded-t-lg flex items-center justify-between sticky top-0 z-10">
                <h2 class="text-base font-bold">Detail Mahasiswa Bimbingan</h2>
                <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Data Mahasiswa</h3>
                    <div class="space-y-2.5">
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Nama Lengkap</p>
                            <p id="modalNama" class="text-sm font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">NPM</p>
                            <p id="modalNPM" class="text-sm font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Perusahaan Tempat Magang</p>
                            <p id="modalPerusahaan" class="text-sm font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Status Pengajuan</p>
                            <p id="modalStatus" class="text-sm font-medium text-gray-900">-</p>
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">File Mahasiswa</h3>
                    <div id="fileContainer" class="space-y-2.5">
                        <p class="text-sm text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg relative animate-fade-in max-h-[90vh] overflow-y-auto">
            <div
                class="bg-violet-700 text-white px-5 py-3.5 rounded-t-lg flex items-center justify-between sticky top-0 z-10">
                <h2 class="text-base font-bold">Edit Evaluasi Laporan</h2>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Laporan yang Dievaluasi</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Jenis Laporan
                        </label>
                        <select id="pilihJenisLaporan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                            <option value="">-- Pilih --</option>
                            <option value="bulanan">laporan mingguan</option>
                            <option value="akhir">Laporan Akhir</option>
                        </select>
                    </div>
                    <div id="editFileContainer" class="space-y-2.5">
                    </div>
                </div>
                <div class="mb-0">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Evaluasi Laporan</h3>
                    <form id="evaluasiForm">
                        @csrf
                        <input type="hidden" id="idLaporan" name="id_laporan">
                        <input type="hidden" id="jenisLaporan" name="jenis_laporan">

                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-1.5">Komentar/Catatan</p>
                            <textarea id="komentarEdit" name="catatan_reviewer" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 resize-none"
                                placeholder="Tulis komentar atau catatan..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 mb-1.5">Status Laporan</p>
                            <select id="statusEdit" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 bg-white"
                                required>
                                <option value="pending">Pending</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak/Revisi</option>
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </button>
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-200 text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
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
let currentPengajuanId = null;
let currentDetailData = null;

// Fungsi untuk membuka modal detail dengan error handling yang lebih baik
async function openDetailModal(idPengajuan) {
    currentPengajuanId = idPengajuan;

    try {
        // Tambahkan loading indicator
        const fileContainer = document.getElementById('fileContainer');
        fileContainer.innerHTML = '<p class="text-sm text-gray-500">Memuat data...</p>';

        const response = await fetch(`/dosen/mahasiswa-bimbingan/${idPengajuan}/detail`);
        
        // Cek apakah response OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();

        // Debug: Log response untuk melihat struktur data
        console.log('Response data:', result);

        if (result.success) {
            currentDetailData = result.data;

            // Isi data mahasiswa
            document.getElementById('modalNama').textContent = result.data.nama || '-';
            document.getElementById('modalNPM').textContent = result.data.npm || '-';
            document.getElementById('modalPerusahaan').textContent = result.data.perusahaan || '-';
            document.getElementById('modalStatus').textContent = result.data.status || '-';

            // Isi file container
            fileContainer.innerHTML = '';

            if (result.data.files && result.data.files.length > 0) {
                result.data.files.forEach(file => {
                    const fileHTML = `
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                                    <p class="text-xs text-gray-500">${file.type} • ${file.size}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="${file.url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1 bg-blue-50 hover:bg-blue-100 rounded transition">
                                    Lihat
                                </a>
                                <a href="${file.url}" download class="text-gray-500 hover:text-gray-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    `;
                    fileContainer.insertAdjacentHTML('beforeend', fileHTML);
                });
            } else {
                fileContainer.innerHTML = '<p class="text-sm text-gray-500">Belum ada file yang diupload</p>';
            }

            // Tampilkan modal
            document.getElementById('detailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            throw new Error(result.message || 'Data tidak valid');
        }
    } catch (error) {
        console.error('Error detail:', error);
        
        // Tampilkan pesan error yang lebih spesifik
        let errorMessage = 'Gagal memuat data mahasiswa';
        if (error.message.includes('404')) {
            errorMessage = 'Data mahasiswa tidak ditemukan (Error 404). Pastikan route sudah benar.';
        } else if (error.message.includes('500')) {
            errorMessage = 'Terjadi kesalahan server (Error 500). Periksa log Laravel.';
        } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
            errorMessage = 'Koneksi gagal. Periksa koneksi internet Anda.';
        }
        
        alert(errorMessage + '\n\nDetail: ' + error.message);
    }
}

// Fungsi untuk tutup modal detail
function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Event listener untuk pilihan jenis laporan
document.getElementById('pilihJenisLaporan').addEventListener('change', function() {
    const jenisLaporan = this.value;
    const editFileContainer = document.getElementById('editFileContainer');
    editFileContainer.innerHTML = '';

    if (!jenisLaporan) {
        document.getElementById('idLaporan').value = '';
        document.getElementById('jenisLaporan').value = '';
        return;
    }

    if (!currentDetailData || !currentDetailData.files) {
        editFileContainer.innerHTML = '<p class="text-sm text-gray-500">Data tidak tersedia</p>';
        return;
    }

    // Filter laporan berdasarkan jenis yang dipilih
    const laporanTerpilih = currentDetailData.files.filter(f => f.jenis === jenisLaporan);

    if (laporanTerpilih.length === 0) {
        editFileContainer.innerHTML = '<p class="text-sm text-gray-500">Belum ada laporan ' + 
            (jenisLaporan === 'bulanan' ? 'bulanan' : 'akhir') + '</p>';
        return;
    }

    // Prioritas: pilih laporan yang masih pending, jika tidak ada pilih yang pertama
    const laporanDipilih = laporanTerpilih.find(f => f.status === 'pending') || laporanTerpilih[0];

    // Set ID dan jenis laporan
    document.getElementById('idLaporan').value = laporanDipilih.id;
    document.getElementById('jenisLaporan').value = jenisLaporan;

    // Tampilkan file yang dipilih
    let statusBadge = '';
    if (laporanDipilih.status === 'disetujui') {
        statusBadge = '<span class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded-full">Disetujui</span>';
    } else if (laporanDipilih.status === 'ditolak') {
        statusBadge = '<span class="text-xs px-2 py-0.5 bg-red-100 text-red-800 rounded-full">Ditolak</span>';
    } else {
        statusBadge = '<span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full">Pending</span>';
    }

    editFileContainer.innerHTML = `
        <div class="flex items-center justify-between py-2.5 border-b border-gray-200">
            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">${laporanDipilih.name}</p>
                    <p class="text-xs text-gray-500">${laporanDipilih.type} • ${statusBadge}</p>
                </div>
            </div>
            <a href="${laporanDipilih.url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1 bg-blue-50 hover:bg-blue-100 rounded transition">
                Lihat
            </a>
        </div>
    `;

    // Isi komentar yang sudah ada (jika laporan sudah dievaluasi)
    document.getElementById('komentarEdit').value = laporanDipilih.komentar_pembimbing || '';

    // Set status yang sudah ada
    document.getElementById('statusEdit').value =
        ['pending', 'disetujui', 'ditolak'].includes(laporanDipilih.status)
            ? laporanDipilih.status 
            : 'pending';
});

// Fungsi untuk tutup modal edit
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Handle submit form evaluasi
document.getElementById('evaluasiForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('idLaporan').value;
    const jenis = document.getElementById('jenisLaporan').value;

    if (!id || !jenis) {
        alert('Silakan pilih jenis laporan terlebih dahulu');
        return;
    }

    let url = '';
    if (jenis === 'bulanan') {
        url = `/laporan-mingguan/${id}/evaluasi`;
    } else if (jenis === 'akhir') {
        url = `/laporan-akhir/${id}/evaluasi`;
    } else {
        alert('Jenis laporan tidak valid');
        return;
    }

    const formData = new FormData(this);

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }

        alert('Evaluasi berhasil disimpan');
        closeEditModal();
        location.reload();

    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan saat menyimpan evaluasi: ' + error.message);
    }
});

// Event listener untuk tutup modal dengan klik di luar atau ESC
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeEditModal();
    }
});
    </script>
@endsection