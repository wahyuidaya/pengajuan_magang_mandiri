@extends('layouts.app_staf-prodi', ['activeMenu' => 'surat_pengantar', 'title' => 'Dashboard Staf Prodi'])
@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Surat Pengantar</h1>

<form method="GET" action="{{ route('staf-prodi.surat_pengantar') }}">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 w-full">
        {{-- Input Search --}}
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama mahasiswa atau NPM..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-purple-500 text-sm">
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

        <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#690dac] text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">NPM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Dosen Pembimbing</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengajuanList as $pengajuan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $pengajuan->mahasiswa->npm ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $pengajuan->mitra->nama_perusahaan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $pengajuan->dosenPembimbing->nama_dosen ?? 'Belum Ditentukan' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pengajuan->surat_pengantar_prodi)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Upload
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Upload
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Icon Download Surat Mahasiswa (Kiri) --}}
                                @if($pengajuan->surat_pengantar_mahasiswa)
                                    <a href="{{ route('staf-prodi.surat_pengantar.download_draft', $pengajuan->id_pengajuan) }}"
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors"
                                        title="Download Surat Mahasiswa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-300 text-gray-500 cursor-not-allowed"
                                        title="Mahasiswa belum upload">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </span>
                                @endif

                                {{-- Icon Kirim/Upload Surat Prodi (Kanan) --}}
                                @if($pengajuan->surat_pengantar_prodi)
                                    {{-- Jika sudah upload, tampilkan icon hapus --}}
                                    <button onclick="confirmDelete('{{ $pengajuan->id_pengajuan }}')"
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors"
                                        title="Hapus Surat Prodi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @else
                                    {{-- Icon Kirim (Pesawat Kertas) --}}
                                    @if($pengajuan->surat_pengantar_mahasiswa)
                                        <button
                                            onclick="openUploadModal('{{ $pengajuan->id_pengajuan }}', '{{ $pengajuan->mahasiswa->nama_mhs ?? '' }}', '{{ $pengajuan->mahasiswa->npm ?? '' }}', '{{ $pengajuan->mitra->nama_perusahaan ?? '' }}', '{{ $pengajuan->dosenPembimbing->nama_dosen ?? '' }}')"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors"
                                            title="Kirim Surat Prodi">
                                            <svg class="w-5 h-5 transform rotate-45 translate-x-0.5 -translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-300 text-gray-500 cursor-not-allowed"
                                            title="Menunggu mahasiswa upload">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada pengajuan yang disetujui
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
                    {{-- PAGINATION --}}
<div class="mt-4 bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-xl shadow-lg">
    <div class="flex-1 flex justify-between items-center sm:hidden">
        {{-- Tampilan Mobile --}}
        @if ($pengajuanList->onFirstPage())
            <span class="text-sm text-gray-400">Sebelumnya</span>
        @else
            <a href="{{ $pengajuanList->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
        @endif

        @if ($pengajuanList->hasMorePages())
            <a href="{{ $pengajuanList->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
        @else
            <span class="text-sm text-gray-400">Berikutnya</span>
        @endif
    </div>

    {{-- Tampilan Desktop --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-600">
                Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} sampai {{ $pengajuanList->lastItem() ?? 0 }} dari total {{ $pengajuanList->total() }} data
            </p>
        </div>

        <div>
            <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                {{-- Tombol Sebelumnya --}}
                @if ($pengajuanList->onFirstPage())
                    <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                @else
                    <a href="{{ $pengajuanList->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-purple-700 transition">Sebelumnya</a>
                @endif

                {{-- Kotak Angka Halaman --}}
                <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm font-medium">
                    {{ $pengajuanList->currentPage() }}
                </div>

                {{-- Tombol Berikutnya --}}
                @if ($pengajuanList->hasMorePages())
                    <a href="{{ $pengajuanList->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-purple-700 transition">Berikutnya</a>
                @else
                    <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                @endif
            </nav>
        </div>
    </div>
</div>
        </div>

        {{-- MODAL UPLOAD --}}
        <div id="uploadModal" class="fixed inset-0 z-50 overflow-y-auto hidden"
            style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full animate-modal">
                    <div class="bg-[#690dac] px-5 py-4 flex justify-between items-center rounded-t-lg">
                        <h3 class="text-lg leading-6 font-medium text-white">Upload Surat Pengantar Prodi</h3>
                        <button onclick="closeUploadModal()" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-5 pt-5 pb-5">
                            <div class="w-full">
                                <div class="mb-4">
                                    <p class="text-xs text-gray-600 mb-1">Nama Mahasiswa</p>
                                    <p id="modalNamaMahasiswa" class="text-base font-bold text-gray-800"></p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        NPM: <span id="modalNPM"></span> • Perusahaan: <span id="modalPerusahaan"></span>
                                    </p>
                                </div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah File Surat</label>
                                <label for="file-upload"
                                    class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-violet-500 transition duration-150 w-full">
                                    <input id="file-upload" name="surat_pengantar" type="file" class="sr-only" accept=".pdf,.docx">
                                    <div>
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        <span class="font-semibold block mt-2" id="fileLabel">Klik atau seret file di sini</span>
                                        <p class="text-xs text-gray-500">Format: PDF atau DOCX (Max 10MB)</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="bg-white px-5 py-3 flex justify-end space-x-3 border-t border-gray-200">
                            <button type="button" onclick="closeUploadModal()"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-5 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Batal
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-5 py-2 bg-purple-700 text-sm font-medium text-white hover:bg-purple-800 focus:outline-none">
                                <svg class="w-5 h-5 transform rotate-45 -translate-x-1 translate-y-0.50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                Kirim Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-modal { animation: slideIn 0.3s ease-out; }
    </style>

    <script>
        let currentIdPengajuan = null;

        function openUploadModal(idPengajuan, nama, npm, perusahaan, dospem) {
            currentIdPengajuan = idPengajuan;
            document.getElementById('modalNamaMahasiswa').textContent = nama;
            document.getElementById('modalNPM').textContent = npm;
            document.getElementById('modalPerusahaan').textContent = perusahaan;
            document.getElementById('uploadModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('file-upload').value = '';
            document.getElementById('fileLabel').textContent = 'Klik atau seret file di sini';
            currentIdPengajuan = null;
        }

        // Handle file selection
        document.getElementById('file-upload')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('fileLabel').textContent = fileName;
            }
        });

        // Handle form submission
        document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('file-upload');
            if (!fileInput.files.length) {
                alert('Silakan pilih file terlebih dahulu!');
                return;
            }

            const formData = new FormData(this);
            
            fetch(`/surat_pengantar/${currentIdPengajuan}/upload`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeUploadModal();
                    location.reload();
                } else {
                    alert('Gagal upload: ' + data.message);
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan saat upload');
                console.error(err);
            });
        });

        // Confirm delete
        function confirmDelete(idPengajuan) {
            if (!confirm('Apakah Anda yakin ingin menghapus surat pengantar dari prodi ini?')) {
                return;
            }

            fetch(`/surat_pengantar/${idPengajuan}/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menghapus: ' + data.message);
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan');
                console.error(err);
            });
        }

        // Close modal on click outside
        document.getElementById('uploadModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeUploadModal();
        });

        // Close modal on ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeUploadModal();
        });
    </script>
@endsection