@extends('layouts.app_staf-prodi', ['activeMenu' => 'arsip_data_magang', 'title' => 'Dashboard Staf Prodi'])

@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Arsip Data Magang</h1>
        
        {{-- FILTER & SEARCH (Disamakan dengan halaman sebelumnya) --}}
        <form method="GET" action="{{ route('staf-prodi.arsip_data_magang') }}" id="filterForm">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama, NPM, atau perusahaan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Tombol Cari Ikon --}}
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

        {{-- TABLE SECTION --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#690dac] text-white">
                        <tr>
                            <th class="px-4 py-4 font-semibold text-sm">Nama Mahasiswa</th>
                            <th class="px-4 py-4 font-semibold text-sm">NPM</th>
                            <th class="px-4 py-4 font-semibold text-sm">Perusahaan</th>
                            <th class="px-4 py-4 font-semibold text-sm">Periode Magang</th>
                            <th class="px-4 py-4 font-semibold text-sm">Dosen Pembimbing</th>
                            <th class="px-4 py-4 font-semibold text-sm">Nilai</th>
                            <th class="px-4 py-4 font-semibold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($arsipMagang as $magang)
                            @php
                                $nilai = $magang->nilaiAkhir->nilai_huruf ?? $magang->nilai_akhir ?? '-';
                                $badgeClass = match(true) {
                                    in_array($nilai, ['A', 'A-']) => 'bg-green-50 text-green-600',
                                    in_array($nilai, ['B+', 'B', 'B-']) => 'bg-yellow-50 text-yellow-600',
                                    in_array($nilai, ['C+', 'C']) => 'bg-orange-50 text-orange-600',
                                    default => 'bg-gray-50 text-gray-600'
                                };

                                $tanggalMagang = '-';
                                if ($magang->tanggal_pengajuan) {
                                    $mulai = $magang->tanggal_pengajuan->format('Y-m-d');
                                    $selesai = $magang->tanggal_pengajuan->copy()->addMonths(3)->format('Y-m-d');
                                    $tanggalMagang = $mulai . ' - ' . $selesai;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-5 text-sm font-medium text-gray-800">{{ $magang->mahasiswa->nama_lengkap }}</td>
                                <td class="px-4 py-5 text-sm text-gray-600">{{ $magang->mahasiswa->npm }}</td>
                                <td class="px-4 py-5 text-sm text-gray-600">{{ $magang->mitra->nama_perusahaan }}</td>
                                <td class="px-4 py-5 text-sm text-gray-600">{{ $tanggalMagang }}</td>
                                <td class="px-4 py-5 text-sm text-gray-600">{{ $magang->dosenPembimbing->nama_dosen ?? '-' }}</td>
                                <td class="px-4 py-5">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badgeClass }}">
                                        {{ $nilai }}
                                    </span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <button onclick="loadArsipModal('{{ $magang->id_pengajuan }}')" class="text-blue-500 hover:text-blue-700 transition">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500 italic">Data arsip tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION (Sesuai Desain Anda) --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
                    @if ($arsipMagang->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $arsipMagang->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($arsipMagang->hasMorePages())
                        <a href="{{ $arsipMagang->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $arsipMagang->firstItem() ?? 0 }} sampai {{ $arsipMagang->lastItem() ?? 0 }} dari total {{ $arsipMagang->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($arsipMagang->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $arsipMagang->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm font-medium">
                                {{ $arsipMagang->currentPage() }}
                            </div>

                            @if ($arsipMagang->hasMorePages())
                                <a href="{{ $arsipMagang->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL (Tetap Sama) --}}
        <div id="arsipModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative animate-fade-in max-h-[90vh] overflow-y-auto">
                <div class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between sticky top-0 z-10">
                    <h2 class="text-base font-bold">Detail Arsip Magang</h2>
                    <button onclick="closeArsipModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div id="modalLoading" class="text-center py-8">
                        <svg class="animate-spin h-8 w-8 mx-auto text-purple-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
                    </div>
                    <div id="modalContent" style="display: none;">
                        {{-- Konten Modal Sama Seperti Sebelumnya --}}
                        <div class="mb-5">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 border-b pb-2">Informasi Mahasiswa</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><p class="text-xs text-gray-500">Nama Lengkap</p><p id="arsipNama" class="font-medium text-gray-900">-</p></div>
                                <div><p class="text-xs text-gray-500">NPM</p><p id="arsipNpm" class="font-medium text-gray-900">-</p></div>
                                <div><p class="text-xs text-gray-500">Perusahaan</p><p id="arsipPerusahaan" class="font-medium text-gray-900">-</p></div>
                                <div><p class="text-xs text-gray-500">Periode Magang</p><p id="arsipTanggal" class="font-medium text-gray-900">-</p></div>
                                <div><p class="text-xs text-gray-500">Dosen Pembimbing</p><p id="arsipDosen" class="font-medium text-gray-900">-</p></div>
                                <div><p class="text-xs text-gray-500">Nilai Akhir</p><p id="arsipNilai" class="font-medium text-gray-900">-</p></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-3 border-b pb-2">Dokumen</h3>
                            <div class="space-y-3" id="dokumenList"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script AJAX dan Modal Tetap Sama --}}
    <script>
        function loadArsipModal(idPengajuan) {
            const modal = document.getElementById('arsipModal');
            const loading = document.getElementById('modalLoading');
            const content = document.getElementById('modalContent');
            modal.style.display = 'flex';
            loading.style.display = 'block';
            content.style.display = 'none';
            document.body.style.overflow = 'hidden';

            fetch(`{{ route('staf-prodi.arsip_data_magang.detail', ':id') }}`.replace(':id', idPengajuan))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('arsipNama').textContent = data.data.nama;
                        document.getElementById('arsipNpm').textContent = data.data.npm;
                        document.getElementById('arsipPerusahaan').textContent = data.data.perusahaan;
                        document.getElementById('arsipTanggal').textContent = data.data.tanggal_magang;
                        document.getElementById('arsipDosen').textContent = data.data.dosen_pembimbing;
                        document.getElementById('arsipNilai').textContent = data.data.nilai_akhir;

                        const dokumenList = document.getElementById('dokumenList');
                        dokumenList.innerHTML = '';
                        if (data.data.dokumen.length === 0) {
                            dokumenList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada dokumen</p>';
                        } else {
                            data.data.dokumen.forEach(dok => {
                                dokumenList.insertAdjacentHTML('beforeend', `
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 transition">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div><p class="text-sm font-medium text-gray-900">${dok.nama}</p><p class="text-xs text-gray-500">PDF • ${dok.ukuran}</p></div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="${dok.url}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">Lihat</a>
                                        </div>
                                    </div>
                                `);
                            });
                        }
                        loading.style.display = 'none';
                        content.style.display = 'block';
                    }
                });
        }
        function closeArsipModal() {
            document.getElementById('arsipModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection