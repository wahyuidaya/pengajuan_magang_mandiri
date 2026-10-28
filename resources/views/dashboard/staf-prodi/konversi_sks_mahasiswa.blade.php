@extends('layouts.app_staf-prodi', ['activeMenu' => 'konversi_sks', 'title' => 'Konversi SKS Mahasiswa'])

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Konversi SKS Mahasiswa</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola Konversi SKS Mahasiswa mahasiswa yang telah mengunggah silabus magang</p>
        </div>

        {{-- FORM PENCARIAN --}}
        <form method="GET" action="{{ route('staf-prodi.konversi_sks') }}">
            <div class="flex items-center gap-3 mb-4 w-full">
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

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-[#690dac] text-white">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">NPM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Perusahaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengajuanList as $pengajuan)
                            @php
                                $mahasiswa = $pengajuan->mahasiswa;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mahasiswa->npm ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mahasiswa->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $pengajuan->mitra->nama_perusahaan ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <button onclick="openDetailModal('{{ $pengajuan->id_pengajuan }}')"
                                        class="inline-flex items-center px-4 py-2 bg-[#690dac] hover:bg-purple-800 text-white text-xs font-semibold rounded-lg transition">
                                        Kelola Konversi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data mahasiswa</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                {{-- Mobile --}}
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

                {{-- Desktop --}}
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} sampai {{ $pengajuanList->lastItem() ?? 0 }} dari total {{ $pengajuanList->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($pengajuanList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $pengajuanList->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $pengajuanList->currentPage() }}
                            </div>

                            @if ($pengajuanList->hasMorePages())
                                <a href="{{ $pengajuanList->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Upload Konversi --}}
    <div id="detailModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen pt-10 px-4 pb-20">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl transform transition-all animate-modal">
                {{-- Header --}}
                <div class="bg-[#690dac] px-6 py-4 rounded-t-xl flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">Upload File Konversi SKS</h3>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="idPengajuan" name="id_pengajuan">

                    <div class="p-6">
                        {{-- Info Mahasiswa --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Mahasiswa</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-600">NPM</p>
                                    <p class="font-semibold text-gray-900" id="detailNpm">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Nama</p>
                                    <p class="font-semibold text-gray-900" id="detailNama">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Dosen Pembimbing</p>
                                    <p class="font-semibold text-gray-900" id="detailDosen">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Perusahaan</p>
                                    <p class="font-semibold text-gray-900" id="detailPerusahaan">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Silabus</p>
                                    <a id="detailSilabus" href="#" target="_blank"
                                        class="inline-flex items-center text-sm font-semibold text-[#690dac] hover:text-purple-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">KRS</p>
                                    <a id="detailKrs" href="#" target="_blank"
                                        class="inline-flex items-center text-sm font-semibold text-[#690dac] hover:text-purple-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Box --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Konversi</label>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#690dac] transition-colors cursor-pointer" id="dropZone">
                                <input type="file" name="file_konversi" id="fileInput" class="hidden" accept=".pdf,.doc,.docx" required>
                                
                                <div id="uploadPlaceholder">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                    <p class="mt-2 text-sm text-gray-600 font-medium">Klik atau drag file di sini</p>
                                    <p class="mt-1 text-xs text-gray-500">Excel, PDF, atau Word (max. 10MB)</p>
                                </div>

                                <div id="filePreview" class="hidden">
                                    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-900" id="fileName"></p>
                                    <p class="mt-1 text-xs text-gray-500" id="fileSize"></p>
                                    <button type="button" onclick="removeFile()" class="mt-3 text-xs text-red-600 hover:text-red-800 font-medium">
                                        Hapus File
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-xs text-blue-800">
                                    <strong>Catatan:</strong> Unggah file berisi daftar mata kuliah yang dikonversi beserta SKS-nya.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end gap-3">
                        <button type="button" onclick="closeDetailModal()"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" id="uploadBtn"
                            class="px-5 py-2 bg-[#690dac] hover:bg-purple-800 text-white text-sm font-semibold rounded-lg transition">
                            Upload
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
    </style>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            @if (session('success'))
                alert("{{ session('success') }}");
            @endif

            @if (session('error'))
                alert("{{ session('error') }}");
            @endif

        });
        let currentIdPengajuan = null;

        // Open Upload Modal
        async function openDetailModal(idPengajuan) {
            currentIdPengajuan = idPengajuan;
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            await loadMahasiswaData(idPengajuan);
        }

        // Close Modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('uploadForm').reset();
            resetFileDisplay();
        }

        // Load Mahasiswa Data
        async function loadMahasiswaData(idPengajuan) {
            try {
                const response = await fetch(`/staf-prodi/konversi-sks/${idPengajuan}/data`);
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('detailNpm').textContent = data.mahasiswa.npm || '-';
                    document.getElementById('detailNama').textContent = data.mahasiswa.nama || '-';
                    document.getElementById('detailDosen').textContent = 
                        data.dosen && data.dosen.nama !== '-'
                            ? data.dosen.nama 
                            : '-';
                    document.getElementById('detailPerusahaan').textContent = data.perusahaan || '-';
                    document.getElementById('detailSilabus').href = data.silabus_url || '#';
                    
                    const krsLink = document.getElementById('detailKrs');
                    if (data.krs_url) {
                        krsLink.href = data.krs_url;
                        krsLink.classList.remove('hidden');
                    } else {
                        krsLink.classList.add('hidden');
                    }
                    
                    document.getElementById('idPengajuan').value = idPengajuan;
                    document.getElementById('uploadForm').action = `/staf-prodi/konversi-sks/${idPengajuan}/upload`;
                }
            } catch (error) {
                console.error('Error loading data:', error);
            }
        }

        // File Upload Handling
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const filePreview = document.getElementById('filePreview');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-[#690dac]', 'bg-purple-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-[#690dac]', 'bg-purple-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-[#690dac]', 'bg-purple-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                displayFile(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displayFile(e.target.files[0]);
            }
        });

        function displayFile(file) {
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            uploadPlaceholder.classList.add('hidden');
            filePreview.classList.remove('hidden');
        }

        function removeFile() {
            fileInput.value = '';
            resetFileDisplay();
        }

        function resetFileDisplay() {
            uploadPlaceholder.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
@endsection