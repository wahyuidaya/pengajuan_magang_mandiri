@extends('layouts.app_mitra-perusahaan', ['activeMenu' => 'kirim_silabus', 'title' => 'Kirim Silabus'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Kirim Silabus kepada Mahasiswa</h1>

        <form method="GET" action="{{ route('mitra-perusahaan.kirim_silabus.index') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa atau NPM..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Tombol Cari Ikon --}}
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
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-[#690dac]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">NPM</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status
                            Pengiriman</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($pengajuanList as $pengajuan)
                        <tr data-id="{{ $pengajuan->id_pengajuan }}">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $pengajuan->mahasiswa->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $pengajuan->mahasiswa->npm }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pengajuan->silabusMitra)
                                    <div
                                        class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Terkirim
                                        <span
                                            class="ml-1 text-xs font-normal text-green-600">{{ date('Y-m-d', strtotime($pengajuan->silabusMitra->tanggal_upload)) }}</span>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Belum Terkirim
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($pengajuan->silabusMitra)
                                    <button disabled
                                        class="px-4 py-2 text-xs font-medium rounded-lg bg-gray-200 text-gray-600 cursor-not-allowed inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1.5 transform rotate-45 -translate-x-0.5 -translate-y-0.5" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Sudah Terkirim
                                    </button>
                                @else
                                    <button
                                        onclick="openUploadSilabusModal('{{ $pengajuan->id_pengajuan }}', '{{ addslashes($pengajuan->mahasiswa->nama) }}')"
                                        class="btn-kirim px-4 py-2 text-xs font-medium rounded-lg bg-[#690dac] text-white hover:bg-violet-800 transition duration-150 inline-flex items-center shadow-sm">
                                        <svg class="w-3 h-3 mr-1.5 transform rotate-45 -translate-x-0.5 -translate-y-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim Silabus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada mahasiswa yang perlu dikirim silabus
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
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

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} sampai {{ $pengajuanList->lastItem() ?? 0 }}
                            dari total {{ $pengajuanList->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($pengajuanList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $pengajuanList->previousPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div
                                class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $pengajuanList->currentPage() }}
                            </div>

                            @if ($pengajuanList->hasMorePages())
                                <a href="{{ $pengajuanList->nextPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Upload Silabus --}}
    <div id="uploadSilabusModal" class="fixed inset-0 z-50 overflow-y-auto hidden"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full animate-modal">
                <form id="uploadSilabusForm" action="{{ route('mitra-perusahaan.kirim_silabus.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="modalIdPengajuan" name="id_pengajuan">

                    <div class="bg-white px-5 py-4 flex justify-between items-center border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Upload Silabus</h3>
                        <button type="button" onclick="closeUploadSilabusModal()"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-5 pt-5 pb-5">
                        <div class="w-full">
                            <p class="text-sm text-gray-600 mb-3">
                                Mahasiswa: <span id="modalMahasiswa" class="font-semibold text-gray-900"></span>
                            </p>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih File Silabus (PDF atau DOCX)
                            </label>
                            <label for="file-silabus-upload" class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-lg
                               bg-gray-50 hover:bg-gray-100 transition cursor-pointer w-full">
                                <div class="space-y-2 text-center pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>

                                    <p class="text-sm text-gray-600 font-semibold">
                                        Klik untuk pilih file
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        atau drag dan drop file di sini
                                    </p>
                                </div>

                                <input id="file-silabus-upload" name="file_silabus" type="file" class="sr-only"
                                    accept=".pdf,.docx" required>
                            </label>

                            <div id="selectedFileName" class="mt-3 text-sm text-gray-600 hidden">
                                <span class="font-medium">File dipilih:</span> <span id="fileName"
                                    class="text-purple-600"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-5 py-3 flex justify-end space-x-3 border-t border-gray-200">
                        <button type="button" onclick="closeUploadSilabusModal()"
                            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-5 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-5 py-2 bg-purple-600 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none">
                            Upload & Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-modal {
            animation: slideIn 0.3s ease-out;
        }

        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                alert("{{ session('success') }}");
            @endif

            @if(session('error'))
                alert("{{ session('error') }}");
            @endif

            function qs(id) {
                return document.getElementById(id);
            }

            /* =============================
               MODAL UPLOAD SILABUS
            ============================== */
            window.openUploadSilabusModal = function (id, mahasiswa) {
                qs('modalIdPengajuan').value = id;
                qs('modalMahasiswa').textContent = mahasiswa;

                qs('uploadSilabusModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeUploadSilabusModal = function () {
                qs('uploadSilabusModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                qs('uploadSilabusForm').reset();

                const selectedFileName = qs('selectedFileName');
                if (selectedFileName) selectedFileName.classList.add('hidden');
            };

            /* =============================
               FILE INPUT SILABUS
            ============================== */
            const silabusInput = qs('file-silabus-upload');
            if (silabusInput) {
                silabusInput.addEventListener('change', e => {
                    if (e.target.files.length) {
                        qs('fileName').textContent = e.target.files[0].name;
                        qs('selectedFileName').classList.remove('hidden');
                    }
                });
            }

            /* =============================
               SEARCH ON ENTER
            ============================= */
            const searchInput = qs('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // cegah default form submit browser (opsional)
                        this.form.submit();  // submit form ke server
                    }
                });
            }


        });
    </script>


@endsection