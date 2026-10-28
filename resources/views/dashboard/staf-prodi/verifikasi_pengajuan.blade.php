@extends('layouts.app_staf-prodi', ['activeMenu' => 'verifikasi_pengajuan', 'title' => 'Dashboard Staf Prodi'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Verifikasi Pengajuan</h1>

        <!-- Filter Status -->
        <form method="GET" action="{{ route('staf-prodi.verifikasi_pengajuan') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa, NPM, atau perusahaan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Select Status --}}
                <div class="relative w-44 sm:w-52">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-gray-700 bg-white appearance-none cursor-pointer text-sm">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan (Diproses)
                        </option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                                class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                                Foto</th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Nama
                                Mahasiswa</th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">NPM
                            </th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Posisi
                            </th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">
                                Perusahaan</th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">IPK
                            </th>
                            <th class="text-white text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Status
                            </th>
                            <th
                                class="text-white text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuanList as $pengajuan)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($pengajuan->mahasiswa->foto_url)
                                        <img src="{{ asset('storage/' . $pengajuan->mahasiswa->foto_url) }}" alt="Foto"
                                            class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-[#690dac] font-semibold text-sm">
                                            {{ strtoupper(substr($pengajuan->mahasiswa->nama_lengkap, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $pengajuan->mahasiswa->nama_lengkap }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pengajuan->mahasiswa->npm }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pengajuan->posisi_dilamar }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pengajuan->nama_perusahaan ?? '-' }}
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pengajuan->mahasiswa->ipk ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                                            'diajukan' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Diproses'],
                                            'verifikasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Verifikasi'],
                                            'revisi' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Revisi'],
                                            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                                            'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                        ];
                                        $config = $statusConfig[$pengajuan->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($pengajuan->status)];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} rounded-full">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-3">
                                        <button onclick="openDetailModal(
                                                            '{{ $pengajuan->mahasiswa->nama_lengkap }}',
                                                            '{{ $pengajuan->mahasiswa->npm }}',
                                                            '{{ $pengajuan->mahasiswa->ipk ?? '-' }}',
                                                            '{{ $pengajuan->mahasiswa->no_hp ?? '-' }}',
                                                            '{{ $pengajuan->posisi_dilamar }}',
                                                            '{{ $pengajuan->nama_perusahaan ?? '-' }}',
                                                            '{{ $pengajuan->periode_magang ?? '-' }}',
                                                            '{{ $pengajuan->id_pengajuan }}',
                                                            '{{ $pengajuan->mahasiswa->foto_url ? asset('storage/' . $pengajuan->mahasiswa->foto_url) : '' }}'
                                                        )" title="Lihat Detail" class="text-blue-500 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>

                                        @if($pengajuan->status == 'diajukan' || $pengajuan->status == 'verifikasi')
                                            <button onclick="openApproveModal('{{ $pengajuan->id_pengajuan }}')"
                                                title="Setujui Pengajuan" class="text-green-500 hover:text-green-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            <button onclick="openRejectModal('{{ $pengajuan->id_pengajuan }}')"
                                                title="Tolak Pengajuan" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @elseif($pengajuan->status == 'ditolak')
                                            {{-- TOMBOL AKTIFKAN: Hanya muncul saat status ditolak --}}
                                                    <form action="{{ route('staf-prodi.verifikasi_pengajuan.reset', $pengajuan->id_pengajuan) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini agar mahasiswa bisa mengajukan kembali?')"
                                                            class="flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 transition shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                </path>
                                                            </svg>
                                                            <span class="text-xs font-bold uppercase">Aktifkan</span>
                                                        </button>
                                                    </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada pengajuan magang.
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
                    @if ($pengajuanList->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $pengajuanList->appends(request()->query())->previousPageUrl() }}"
                            class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($pengajuanList->hasMorePages())
                        <a href="{{ $pengajuanList->appends(request()->query())->nextPageUrl() }}"
                            class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                {{-- Tampilan Desktop --}}
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} sampai {{ $pengajuanList->lastItem() ?? 0 }}
                            dari total {{ $pengajuanList->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            {{-- Tombol Sebelumnya --}}
                            @if ($pengajuanList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $pengajuanList->appends(request()->query())->previousPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            {{-- Kotak Angka Halaman --}}
                            <div
                                class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $pengajuanList->currentPage() }}
                            </div>

                            {{-- Tombol Berikutnya --}}
                            @if ($pengajuanList->hasMorePages())
                                <a href="{{ $pengajuanList->appends(request()->query())->nextPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div id="detailModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-sm relative animate-fade-in max-h-[90vh] overflow-y-auto">
                <div
                    class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between sticky top-0 z-10">
                    <h2 class="text-base font-bold">Detail Pengajuan</h2>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="flex justify-center mb-5">
                        <div id="modalProfilePicture"
                            class="w-20 h-20 rounded-full bg-purple-600 flex items-center justify-center overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Data Diri Mahasiswa</h3>
                        <div class="space-y-2.5">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Nama Lengkap</p>
                                    <p id="modalNama" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">NPM</p>
                                    <p id="modalNPM" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">IPK</p>
                                    <p id="modalIPK" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Nomor HP</p>
                                    <p id="modalHP" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Data Perusahaan</h3>
                        <div class="space-y-2.5">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Posisi di Perusahaan</p>
                                    <p id="modalPosisi" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Nama Perusahaan</p>
                                    <p id="modalPerusahaan" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Periode Magang</p>
                                <p id="modalPeriode" class="text-sm font-medium text-gray-900">-</p>
                            </div>

                        </div>
                    </div>
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Dokumen Pengajuan</h3>
                        <div class="space-y-3" id="dokumenList">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Approve -->
        <div id="approveModal" class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="bg-green-600 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between">
                    <h2 class="text-base font-bold">Setujui Pengajuan</h2>
                    <button onclick="closeApproveModal()" class="text-white hover:text-gray-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="approveForm" method="POST" class="p-5">
                    @csrf
                    <p class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin menyetujui pengajuan ini?</p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan_verifikator" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeApproveModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Setujui</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Reject -->
        <div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div class="bg-red-600 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between">
                    <h2 class="text-base font-bold">Tolak Pengajuan</h2>
                    <button onclick="closeRejectModal()" class="text-white hover:text-gray-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="rejectForm" method="POST" class="p-5">
                    @csrf
                    <p class="text-sm text-gray-700 mb-4">Apakah Anda yakin ingin menolak pengajuan ini?</p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                        <textarea name="catatan_verifikator" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
                    </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                window.alert("{{ session('success') }}");
            @endif

            @if(session('error'))
                window.alert("{{ session('error') }}");
            @endif

                @if(session('notif_tentukan_dospem'))
                    let notifMessage = "Pengajuan telah disetujui. Silakan tentukan dosen pembimbing dan mitra perusahaan untuk mahasiswa terkait.";
                    let notifUrl = "{{ session('notif_url') }}";
                    if (confirm(notifMessage + "\nKlik OK untuk menuju penentuan dosen pembimbing dan mitra perusahaan.")) {
                        window.location.href = notifUrl;
                    }
                @endif
    });

        let currentPengajuanId = null;

        // =========================================================================
        // PERBAIKAN DI SINI: Tambahkan 'periode' ke dalam parameter fungsi (urutan ke-7)
        // =========================================================================
        function openDetailModal(nama, npm, ipk, hp, posisi, perusahaan, periode, idPengajuan, fotoUrl) {
            currentPengajuanId = idPengajuan;

            // Set text content
            document.getElementById('modalNama').textContent = nama;
            document.getElementById('modalNPM').textContent = npm;
            document.getElementById('modalIPK').textContent = ipk;
            document.getElementById('modalHP').textContent = hp;
            document.getElementById('modalPosisi').textContent = posisi;
            document.getElementById('modalPerusahaan').textContent = perusahaan;
            document.getElementById('modalPeriode').textContent = periode; // Sekarang ini tidak akan error

            // Handle Profile Picture
            const profilePicture = document.getElementById('modalProfilePicture');
            if (fotoUrl && fotoUrl !== '') {
                profilePicture.innerHTML = `<img src="${fotoUrl}" alt="Foto Profil" class="w-full h-full object-cover">`;
            } else {
                const initials = nama.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                profilePicture.innerHTML = `<div class="w-full h-full flex items-center justify-center bg-purple-600 text-white text-2xl font-bold">${initials}</div>`;
            }

            // Handle Dokumen List
            const dokumenList = document.getElementById('dokumenList');
            const baseUrl = '{{ url("verifikasi_pengajuan") }}';

            dokumenList.innerHTML = `
        <!-- KRS -->
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">KRS</p>
                    <p class="text-xs text-gray-500">PDF • 245 KB</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="${baseUrl}/${idPengajuan}/view/krs" target="_blank" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition flex items-center gap-1.5">Lihat</a>
                <a href="${baseUrl}/${idPengajuan}/download/krs" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Unduh">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Transkrip Nilai -->
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Transkrip Nilai</p>
                    <p class="text-xs text-gray-500">PDF • 300 KB</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="${baseUrl}/${idPengajuan}/view/transkrip" target="_blank" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition flex items-center gap-1.5">Lihat</a>
                <a href="${baseUrl}/${idPengajuan}/download/transkrip" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Unduh">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Surat Pengantar -->
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Surat Pengantar</p>
                    <p class="text-xs text-gray-500">PDF • 100 KB</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="${baseUrl}/${idPengajuan}/view/surat" target="_blank" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition flex items-center gap-1.5">Lihat</a>
                <a href="${baseUrl}/${idPengajuan}/download/surat" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Unduh">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
            </div>
        </div>
    `;

            document.getElementById('detailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Fungsi Approve & Reject (Sudah Benar)
        function openApproveModal(id) {
            document.getElementById('approveForm').action = `{{ url('verifikasi_pengajuan') }}/${id}/approve`;
            document.getElementById('approveModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeApproveModal() {
            document.getElementById('approveModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `{{ url('verifikasi_pengajuan') }}/${id}/reject`;
            document.getElementById('rejectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close on outside click
        window.onclick = function (event) {
            let detailModal = document.getElementById('detailModal');
            let approveModal = document.getElementById('approveModal');
            let rejectModal = document.getElementById('rejectModal');
            if (event.target == detailModal) closeDetailModal();
            if (event.target == approveModal) closeApproveModal();
            if (event.target == rejectModal) closeRejectModal();
        }
    </script>

@endsection