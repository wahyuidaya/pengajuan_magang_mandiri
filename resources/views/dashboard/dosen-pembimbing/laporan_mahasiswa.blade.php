@extends('layouts.app_dosen-pembimbing', ['activeMenu' => 'laporan_mahasiswa', 'title' => 'Laporan Mahasiswa'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Mahasiswa</h1>
        <form method="GET" action="{{ route('dosen-pembimbing.laporan_mahasiswa') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa, NPM, atau judul laporan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>
                {{-- Bagian Select Filter --}}
                <div class="relative w-44 sm:w-52">
                    <select name="status" id="filterStatus"
                        onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-gray-700 bg-white appearance-none cursor-pointer text-sm">
                        <option value="">Semua</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Akhir</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute right-3 top-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
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
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">NPM</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Tipe Laporan</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Judul Laporan</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($laporanList as $index => $laporan)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-medium">{{ $laporan['mahasiswa']->nama_lengkap }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $laporan['mahasiswa']->npm }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full 
                                {{ $laporan['tipe'] == 'bulanan' ? 'bg-violet-100 text-violet-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $laporan['tipe'] == 'bulanan' ? 'Bulanan' : 'Akhir' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $laporan['judul_laporan'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ \Carbon\Carbon::parse($laporan['tanggal_upload'])->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusBadge = match($laporan['status']) {
                                    'disetujui' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'revisi' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-blue-100 text-blue-800'
                                };
                                $statusLabel = match($laporan['status']) {
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'revisi' => 'Revisi',
                                    default => 'Diperiksa'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <button
                                onclick='openReviewModal(@json($laporan))'
                                class="text-violet-600 hover:text-violet-900 transition duration-150 inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="font-medium text-sm">Lihat</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            Belum ada laporan mahasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
                    {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
                    @if ($laporanList->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $laporanList->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($laporanList->hasMorePages())
                        <a href="{{ $laporanList->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $laporanList->firstItem() ?? 0 }} sampai {{ $laporanList->lastItem() ?? 0 }} dari total {{ $laporanList->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($laporanList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $laporanList->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $laporanList->currentPage() }}
                            </div>

                            @if ($laporanList->hasMorePages())
                                <a href="{{ $laporanList->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative animate-fade-in">
            <button onclick="closeReviewModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="px-5 pt-4 pb-3">
                <h2 class="text-base font-bold text-gray-900">Review Laporan</h2>
            </div>
            
            <div class="px-5 pb-4">
                <div class="space-y-2.5 mb-4">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Nama Mahasiswa</p>
                            <p id="reviewNama" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">NPM</p>
                            <p id="reviewNPM" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Tipe Laporan</p>
                            <p id="reviewTipe" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-0.5">Tanggal Upload</p>
                            <p id="reviewTanggal" class="font-medium text-gray-900">-</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-0.5">Judul Laporan</p>
                        <p id="reviewJudul" class="font-medium text-gray-900">-</p>
                    </div>
                </div>
                
                <hr class="border-gray-200 mb-4">
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1.5">Status Laporan</label>
                        <select id="statusLaporan" class="w-full px-3 py-2 border rounded-lg">
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak / Revisi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-1.5">Komentar/Catatan</label>
                        <textarea id="komentarReview" rows="3"
                            placeholder="Berikan komentar atau catatan untuk mahasiswa..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-violet-500 focus:border-violet-500 resize-none"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="px-5 pb-4 flex gap-3">
                <button
                    onclick="simpanReview()"
                    class="flex-1 bg-violet-700 hover:bg-violet-800 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-sm">
                    Simpan Review
                </button>
                <button onclick="closeReviewModal()"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }
    </style>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            alert("Berhasil: {{ session('success') }}");
        @endif

        @if (session('error'))
            alert("Gagal: {{ session('error') }}");
        @endif
    });

        let currentLaporanId = null;
        let currentLaporanTipe = null;

        function openReviewModal(laporan) {
            currentLaporanId = laporan.id;
            currentLaporanTipe = laporan.tipe;

            document.getElementById('reviewNama').textContent = laporan.mahasiswa.nama_lengkap;
            document.getElementById('reviewNPM').textContent = laporan.mahasiswa.npm;
            document.getElementById('reviewTipe').textContent =
                laporan.tipe === 'bulanan' ? 'laporan mingguan' : 'Laporan Akhir';

            const tanggalUpload = new Date(laporan.tanggal_upload);
            document.getElementById('reviewTanggal').textContent =
                tanggalUpload.toISOString().split('T')[0];

            document.getElementById('reviewJudul').textContent = laporan.judul_laporan;
            document.getElementById('statusLaporan').value =
                ['pending', 'disetujui', 'ditolak'].includes(laporan.status)
                    ? laporan.status
                    : 'pending';

            document.getElementById('komentarReview').value = laporan.komentar_pembimbing || '';

            document.getElementById('reviewModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentLaporanId = null;
            currentLaporanTipe = null;
        }

        async function simpanReview() {
            const status = document.getElementById('statusLaporan').value;
            const komentar = document.getElementById('komentarReview').value;

            if (!komentar.trim()) {
                alert('Mohon isi komentar terlebih dahulu');
                return;
            }

            if (!currentLaporanId || !currentLaporanTipe) {
                alert('Data laporan tidak valid');
                return;
            }

            // ✅ AMBIL CSRF TOKEN (DI LUAR headers)
            const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');

            if (!csrfTokenEl) {
                alert('CSRF token tidak ditemukan. Periksa layout.');
                return;
            }

            const csrfToken = csrfTokenEl.content;

            try {
                const response = await fetch(
                    `/dosen-pembimbing/laporan-mahasiswa/${currentLaporanId}/review`,
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            tipe: currentLaporanTipe,
                            status: status,
                            catatan_reviewer: komentar
                        })
                    }
                );

                const result = await response.json();

                if (result.success) {
                    alert('Review berhasil disimpan');
                    closeReviewModal();
                    location.reload();
                } else {
                    alert(result.message || 'Gagal menyimpan review');
                }

            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat menyimpan review');
            }
        }

        // Close modal when clicking outside
        document.getElementById('reviewModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeReviewModal();
            }
        });
    </script>
@endsection