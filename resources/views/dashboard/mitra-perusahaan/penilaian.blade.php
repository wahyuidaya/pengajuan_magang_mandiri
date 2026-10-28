@extends('layouts.app_mitra-perusahaan', ['activeMenu' => 'penilaian', 'title' => 'Penilaian Mahasiswa Magang'])
@section('content')
    <div class="max-w-6xl mx-auto">
        
        {{-- Header Area: Judul & Tombol --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Penilaian Mahasiswa Magang</h1>

        <a href="https://drive.google.com/drive/folders/1CtCa_H8i-_sqVSfv03M0Afc_WTBAIYMl?usp=sharing" 
        target="_blank"
        class="flex items-center space-x-2 px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-200 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Template Penilaian
        </a>
        </div>
<form method="GET" action="{{ route('mitra-perusahaan.penilaian') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                {{-- Input Search --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa atau NPM..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>

                {{-- Select Status Penilaian --}}
                <div class="relative w-40 sm:w-48">
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-gray-700 bg-white appearance-none cursor-pointer text-sm">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dinilai</option>
                        <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Dinilai</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                {{-- Tombol Cari --}}
                <button type="submit"
                    class="p-2.5 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">File Penilaian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($pengajuanList as $pengajuan)
                        <tr data-npm="{{ $pengajuan->mahasiswa->npm }}" data-nama="{{ $pengajuan->mahasiswa->nama_lengkap }}">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                {{ $pengajuan->mahasiswa->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $pengajuan->mahasiswa->npm }}
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    @if(!empty($pengajuan->periode_magang))
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $pengajuan->periode_magang }}
                                        </span>
                                        @if($pengajuan->penilaian && $pengajuan->penilaian->tanggal_upload_formatted)
                                            <span class="block text-xs text-gray-500">
                                                Dinilai: {{ $pengajuan->penilaian->tanggal_upload_formatted }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                @if($pengajuan->penilaian && $pengajuan->penilaian->file_penilaian)
                                    <span class="text-xs text-[#690dac] font-medium block">
                                        {{ basename($pengajuan->penilaian->file_penilaian) }}
                                    </span>
                                @else
                                    Belum ada
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pengajuan->penilaian && $pengajuan->penilaian->nilai_akhir)
                                    <span
                                        class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if(!$pengajuan->penilaian || !$pengajuan->penilaian->nilai_akhir)
                                    <button
                                        onclick="openUploadModal('{{ $pengajuan->mahasiswa->nama_lengkap }}', '{{ $pengajuan->mahasiswa->npm }}', '{{ $pengajuan->id_pengajuan }}')"
                                        class="text-[#690dac] hover:text-violet-900 transition duration-150 p-1.5 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </button>
                                @else
                                    <button disabled class="text-gray-400 p-1.5 rounded-lg cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada mahasiswa yang perlu dinilai
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
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
                                <a href="{{ $pengajuanList->appends(request()->query())->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $pengajuanList->currentPage() }}
                            </div>

                            @if ($pengajuanList->hasMorePages())
                                <a href="{{ $pengajuanList->appends(request()->query())->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="uploadPenilaianModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full relative animate-fade-in">
            <div class="bg-[#690dac] text-white p-4 rounded-t-xl flex justify-between items-center">
                <h2 class="text-lg font-bold">Upload File Penilaian</h2>
                <button onclick="closeUploadModal()" class="text-white/80 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <p class="text-sm font-medium text-gray-700">Mahasiswa</p>
                    <p id="modalNama" class="text-base font-semibold text-gray-900 mt-0.5">Wahyu Idaya</p>
                    <p id="modalNPM" class="text-sm text-gray-600">11122465</p>
                </div>
                <p class="text-sm font-medium text-gray-700 mb-2">Upload File Penilaian</p>
<div id="dropArea" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-violet-500 transition duration-150">
    <input type="file" id="fileInput" class="hidden" accept=".xlsx, .pdf, .docx, .doc">
    <label for="fileInput" class="block cursor-pointer">
        <div id="fileIconContainer">
            <svg id="defaultIcon" class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
        </div>
        <p id="fileName" class="text-sm text-gray-600 font-medium">Klik atau drag file di sini</p>
        <p id="fileSubText" class="text-xs text-gray-400 mt-1">Excel, PDF, atau Word (max 10MB)</p>
    </label>
</div>
                <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                    <p class="text-xs text-blue-800 font-medium">Catatan:</p>
                    <p class="text-xs text-blue-700 mt-0.5">Unggah file berisi daftar nilai untuk semua mata kuliah yang
                        dinilai.</p>
                </div>
            </div>
            <div class="p-5 pt-0 flex justify-end gap-3">
                <button onclick="closeUploadModal()"
                    class="px-5 py-2 text-gray-700 hover:bg-gray-100 font-medium rounded-lg transition duration-200 text-sm">
                    Batal
                </button>
            <button onclick="simpanUpload(event)"
                class="px-5 py-2 bg-[#690dac] hover:bg-violet-800 text-white font-medium rounded-lg transition duration-200 text-sm">
                Upload
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
    let currentIdPengajuan = null;
    const uploadArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');

    // 1. FUNGSI MODAL
    function openUploadModal(nama, npm, idPengajuan) {
        currentIdPengajuan = idPengajuan;
        document.getElementById('modalNama').textContent = nama;
        document.getElementById('modalNPM').textContent = npm;
        document.getElementById('uploadPenilaianModal').style.display = 'flex';
        
        resetFileDisplay(); // Memastikan modal bersih saat dibuka
        document.body.style.overflow = 'hidden';
    }

    function closeUploadModal() {
        document.getElementById('uploadPenilaianModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        currentIdPengajuan = null;
    }

    // 2. LOGIKA TAMPILAN FILE
    function updateFileDisplay(input) {
        const fileNameDisplay = document.getElementById('fileName');
        const fileSubText = document.getElementById('fileSubText');
        const iconContainer = document.getElementById('fileIconContainer');

        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            fileNameDisplay.textContent = "File terpilih: " + file.name;
            fileNameDisplay.classList.remove('text-gray-600');
            fileNameDisplay.classList.add('text-violet-600', 'font-bold');
            
            fileSubText.textContent = (file.size / 1024 / 1024).toFixed(2) + " MB";
            uploadArea.classList.add('border-violet-500', 'bg-violet-50');

            iconContainer.innerHTML = `
                <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>`;
        }
    }

    function resetFileDisplay() {
        fileInput.value = ''; 
        const fileNameDisplay = document.getElementById('fileName');
        fileNameDisplay.textContent = "Klik atau drag file di sini";
        fileNameDisplay.classList.remove('text-violet-600', 'font-bold');
        fileNameDisplay.classList.add('text-gray-600');
        
        document.getElementById('fileSubText').textContent = "Excel, PDF, atau Word (max 10MB)";
        uploadArea.classList.remove('border-violet-500', 'bg-violet-50');
        document.getElementById('fileIconContainer').innerHTML = `
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>`;
    }

    // 3. EVENT LISTENERS
    fileInput.addEventListener('change', function() {
        updateFileDisplay(this);
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-violet-500', 'bg-violet-50');
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        if (fileInput.files.length === 0) {
            uploadArea.classList.remove('border-violet-500', 'bg-violet-50');
        }
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            updateFileDisplay(fileInput);
        }
    });

    // 4. FUNGSI UPLOAD (AJAX)
    function simpanUpload(event) {
        if (fileInput.files.length === 0) {
            alert('Silakan pilih file untuk diunggah.');
            return;
        }

        const formData = new FormData();
        formData.append('file_penilaian', fileInput.files[0]);
        formData.append('id_pengajuan', currentIdPengajuan);
        formData.append('_token', '{{ csrf_token() }}');

        const uploadBtn = event.target;
        const originalText = uploadBtn.textContent;
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Uploading...';

        fetch('{{ route("mitra-perusahaan.penilaian.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`File berhasil diunggah!`);
                closeUploadModal();
                window.location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => alert('Terjadi kesalahan saat mengunggah file'))
        .finally(() => {
            uploadBtn.disabled = false;
            uploadBtn.textContent = originalText;
        });
    }

    // Close Modal Events
    document.getElementById('uploadPenilaianModal').addEventListener('click', function (e) {
        if (e.target === this) closeUploadModal();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeUploadModal(); });

    // Search & Status Filter
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const nama = row.getAttribute('data-nama')?.toLowerCase() || '';
                const npm = row.getAttribute('data-npm')?.toLowerCase() || '';
                row.style.display = (nama.includes(searchValue) || npm.includes(searchValue)) ? '' : 'none';
            });
        });
    }
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) { statusSelect.addEventListener('change', function () { this.form.submit(); }); }
</script>

@endsection