@extends('layouts.app_staf-prodi', ['activeMenu' => 'staf_prodi_data_mahasiswa', 'title' => 'Dashboard Staf Prodi'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Data Mahasiswa</h1>


        @if (session('success'))
            <div id="alert-success" data-message="{{ session('success') }}"></div>
        @endif

        @if (session('error'))
            <div id="alert-error" data-message="{{ session('error') }}"></div>
        @endif



<form method="GET" action="{{ route('staf-prodi.staf_prodi_data_mahasiswa') }}">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
        {{-- Input Search --}}
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama mahasiswa atau NPM..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
        </div>

        {{-- Filter Status --}}
        <div class="relative w-44 sm:w-52">
            <select name="status"
                onchange="this.form.submit()"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-gray-700 bg-white appearance-none cursor-pointer text-sm">
                <option value="">Semua Status</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Aktif</option>
                <option value="non_aktif" {{ request('status') == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
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
            <div class="w-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-[#690dac]">
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                                NPM</th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">Nama
                                Mahasiswa</th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">Kelas
                            </th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">
                                Perusahaan</th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">Dosen
                                Pembimbing</th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">Status
                            </th>
                            <th class="text-white text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                                Aksi</th>
                        </tr>
                    </thead>
<tbody class="divide-y divide-gray-100">
    @forelse($mahasiswaList as $mahasiswa)
        @php
            $pengajuan = $mahasiswa->pengajuanMagang->first();
            $dosen = $pengajuan && $pengajuan->id_dosen ? \App\Models\DosenPembimbing::find($pengajuan->id_dosen) : null;
            
            // Check apakah mata kuliah eligible sudah di-set
            $hasMatkulEligible = $pengajuan && !empty($pengajuan->mata_kuliah_eligible);
        @endphp
        <tr class="hover:bg-gray-50 transition duration-150">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mahasiswa->npm }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->nama_lengkap }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $mahasiswa->kelas ?? '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                @if($pengajuan)
                    <div id="mitra-cell-{{ $pengajuan->id_pengajuan }}">
                        @if($pengajuan->id_mitra)
                            <span class="text-gray-900 font-medium">{{ $pengajuan->mitra->nama_perusahaan }}</span>
                        @else
                            <button 
                                onclick="openMitraDropdown('{{ $pengajuan->id_pengajuan }}')"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tentukan Perusahaan
                            </button>
                        @endif
                    </div>

                    <div id="mitra-dropdown-{{ $pengajuan->id_pengajuan }}" class="hidden mt-2">
                        <select id="select-mitra-{{ $pengajuan->id_pengajuan }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach($mitraList as $m)
                                <option value="{{ $m->id_mitra }}">{{ $m->nama_perusahaan }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2 mt-2">
                            <button onclick="saveMitraInline('{{ $pengajuan->id_pengajuan }}')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Simpan
                            </button>
                            <button onclick="closeMitraDropdown('{{ $pengajuan->id_pengajuan }}')"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Batal
                            </button>
                        </div>
                    </div>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                @if($pengajuan)
                    <div id="dosen-cell-{{ $pengajuan->id_pengajuan }}">
                        @if($dosen)
                            <span class="text-gray-900 font-medium">{{ $dosen->nama_dosen }}</span>
                        @else
                            <button 
                                onclick="openDosenDropdown('{{ $pengajuan->id_pengajuan }}')"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tentukan Dospem
                            </button>
                        @endif
                    </div>
                    
                    <div id="dosen-dropdown-{{ $pengajuan->id_pengajuan }}" class="hidden mt-2">
                        <select id="select-dosen-{{ $pengajuan->id_pengajuan }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                                <option value="{{ $d->id_dosen }}">{{ $d->nama_dosen }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-2 mt-2">
                            <button onclick="saveDosenInline('{{ $pengajuan->id_pengajuan }}')"
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Simpan
                            </button>
                            <button onclick="closeDosenDropdown('{{ $pengajuan->id_pengajuan }}')"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Batal
                            </button>
                        </div>
                    </div>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($pengajuan)
                    @php
                        $statusClasses = [
                            'disetujui' => 'bg-green-100 text-green-800',
                            'non_aktif' => 'bg-red-100 text-red-800',
                            'draft' => 'bg-gray-100 text-gray-800'
                        ];
                        $class = $statusClasses[$pengajuan->status] ?? 'bg-blue-100 text-blue-800';
                    @endphp
                        @php
                            $statusMap = [
                                'diajukan'  => 'Aktif',
                                'disetujui' => 'Aktif',
                                'non_aktif' => 'Non Aktif',
                                'draft'     => 'Draft'
                            ];

                            $statusClassMap = [
                                'diajukan'  => 'bg-green-100 text-green-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'non_aktif' => 'bg-red-100 text-red-800',
                                'draft'     => 'bg-gray-100 text-gray-800'
                            ];

                            $label = $statusMap[$pengajuan->status] ?? ucfirst($pengajuan->status);
                            $class = $statusClassMap[$pengajuan->status] ?? 'bg-blue-100 text-blue-800';
                        @endphp

                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium {{ $class }} rounded-full">
                            {{ $label }}
                        </span>

                @else
                    <span class="text-gray-400 text-xs">Belum Ada Pengajuan</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center justify-center space-x-3">
                    {{-- Button Lihat Detail --}}
                    <button onclick="openProfilModal('{{ $mahasiswa->npm }}')"
                        title="Lihat Detail" class="text-blue-500 hover:text-blue-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>

                    {{-- Button Aktifkan Kembali (Hanya muncul jika status non_aktif) --}}
                    @if($pengajuan && $pengajuan->status == 'non_aktif')
                        <form action="{{ route('staf-prodi.verifikasi_pengajuan.activate', $pengajuan->id_pengajuan) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali pengajuan ini?')"
                                class="flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
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
            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                Belum ada data mahasiswa.
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
        @if ($mahasiswaList->onFirstPage())
            <span class="text-sm text-gray-400">Sebelumnya</span>
        @else
            <a href="{{ $mahasiswaList->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
        @endif

        @if ($mahasiswaList->hasMorePages())
            <a href="{{ $mahasiswaList->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
        @else
            <span class="text-sm text-gray-400">Berikutnya</span>
        @endif
    </div>

    {{-- Tampilan Desktop --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-600">
                Menampilkan {{ $mahasiswaList->firstItem() ?? 0 }} sampai {{ $mahasiswaList->lastItem() ?? 0 }} dari total {{ $mahasiswaList->total() }} data
            </p>
        </div>

        <div>
            <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                {{-- Tombol Sebelumnya --}}
                @if ($mahasiswaList->onFirstPage())
                    <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                @else
                    <a href="{{ $mahasiswaList->appends(request()->query())->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                @endif

                {{-- Kotak Angka Halaman --}}
                <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                    {{ $mahasiswaList->currentPage() }}
                </div>

                {{-- Tombol Berikutnya --}}
                @if ($mahasiswaList->hasMorePages())
                    <a href="{{ $mahasiswaList->appends(request()->query())->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                @else
                    <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                @endif
            </nav>
        </div>
    </div>
</div>
        </div>
        <!-- Modal Profil Mahasiswa -->
        <div id="profilModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative animate-fade-in max-h-[90vh] overflow-y-auto">
                <div class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between sticky top-0 z-10">
                    <h2 class="text-base font-bold">Profil Mahasiswa</h2>
                    <button onclick="closeProfilModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi Pribadi</h3>
                        <div class="space-y-2.5">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Nama Lengkap</p>
                                    <p id="profilNama" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">NPM</p>
                                    <p id="profilNPM" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Kelas</p>
                                    <p id="profilKelas" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">IPK</p>
                                    <p id="profilIPK" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Nomor HP</p>
                                    <p id="profilHP" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Perusahaan Magang</p>
                                    <p id="profilPerusahaan" class="font-medium text-gray-900">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-0.5">Periode Magang</p>
                                    <p id="profilPeriode" class="font-medium text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Dokumen</h3>
                        <div class="space-y-3" id="dokumenList">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="border border-gray-200 rounded-lg p-3 md:col-span-2">
                            <p class="text-xs text-gray-600 mb-2">Perusahaan Magang</p>
                            <div id="mitraViewMode" class="flex items-center justify-between">
                                <p id="profilMitra" class="text-sm font-medium text-gray-900">-</p>
                                <button onclick="toggleMitraEdit(true)" class="text-blue-600 hover:text-blue-800 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                    a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="mitraEditMode" class="hidden">
                                <select id="selectMitra" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2 focus:ring-2 focus:ring-blue-500">
                                    @foreach($mitraList as $m)
                                        <option value="{{ $m->id_mitra }}">{{ $m->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                                <div class="flex gap-2">
                                    <button onclick="saveMitraModal()" class="flex-1 bg-blue-600 text-white py-1.5 rounded-lg text-xs">Simpan</button>
                                    <button onclick="toggleMitraEdit(false)" class="flex-1 bg-gray-100 text-gray-700 py-1.5 rounded-lg text-xs">Batal</button>
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-3 md:col-span-2">
                            <p class="text-xs text-gray-600 mb-2">Dosen Pembimbing</p>
                            <div id="dosenViewMode" class="flex items-center justify-between">
                                <p id="profilDosen" class="text-sm font-medium text-gray-900">-</p>
                                <button onclick="toggleDosenEdit(true)"
                                    class="text-blue-600 hover:text-blue-800 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                    a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="dosenEditMode" class="hidden">
                                <select id="selectDosen"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2 focus:ring-2 focus:ring-purple-500">
                                    @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id_dosen }}">{{ $dosen->nama_dosen }}</option>
                                    @endforeach
                                </select>
                                <div class="flex gap-2">
                                    <button onclick="saveDosenPembimbing()"
                                        class="flex-1 bg-purple-700 text-white py-1.5 rounded-lg text-xs">Simpan</button>
                                    <button onclick="toggleDosenEdit(false)"
                                        class="flex-1 bg-gray-100 text-gray-700 py-1.5 rounded-lg text-xs">Batal</button>
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-3 md:col-span-2">
                            <p class="text-xs text-gray-600 mb-1">Nilai Akhir (Prodi)</p>
                            <p id="profilNilaiAkhir" class="text-sm font-medium text-gray-900">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.2s ease-out; }
        
        /* Force hide button */
        .force-hide {
            display: none !important;
        }
    </style>

    <script>

    document.addEventListener('DOMContentLoaded', function () {

    const successEl = document.getElementById('alert-success');
    const errorEl   = document.getElementById('alert-error');

    if (successEl) {
        alert(successEl.dataset.message);
    }

    if (errorEl) {
        alert(errorEl.dataset.message);
    }

});

        let currentData = {
            idPengajuan: null,
            idDosen: null,
            nilaiAkhir: null
        };

        let matkulRowCounter = 1;

        // ===== INLINE DOSEN FUNCTIONS =====
        function openDosenDropdown(idPengajuan) {
            const dropdown = document.getElementById(`dosen-dropdown-${idPengajuan}`);
            dropdown.classList.remove('hidden');
        }

        function closeDosenDropdown(idPengajuan) {
            const dropdown = document.getElementById(`dosen-dropdown-${idPengajuan}`);
            dropdown.classList.add('hidden');
        }

        async function saveDosenInline(idPengajuan) {
            const selectElement = document.getElementById(`select-dosen-${idPengajuan}`);
            const idDosen = selectElement.value;

            if (!idDosen) {
                alert('Mohon pilih dosen pembimbing terlebih dahulu!');
                return;
            }

            const cell = document.getElementById(`dosen-cell-${idPengajuan}`);
            const originalContent = cell.innerHTML;
            cell.innerHTML = '<span class="text-gray-500 text-sm">Menyimpan...</span>';

            try {
                const response = await fetch('{{ route("staf-prodi.data_mahasiswa.update_dosen") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_pengajuan: idPengajuan,
                        id_dosen: idDosen
                    })
                });

                const result = await response.json();

                if (result.success) {
                    cell.innerHTML = `<span class="text-gray-900 font-medium">${result.data.nama_dosen}</span>`;
                    alert('Dosen pembimbing berhasil ditentukan!');
                } else {
                    cell.innerHTML = originalContent;
                    alert(result.message || 'Gagal memperbarui dosen pembimbing.');
                }
            } catch (error) {
                cell.innerHTML = originalContent;
                console.error('Full error:', error);
                alert('Error: ' + error.message);
            }
        }

        // ===== INLINE MITRA FUNCTIONS =====
        function openMitraDropdown(idPengajuan) {
            document.getElementById(`mitra-dropdown-${idPengajuan}`).classList.remove('hidden');
        }

        function closeMitraDropdown(idPengajuan) {
            document.getElementById(`mitra-dropdown-${idPengajuan}`).classList.add('hidden');
        }

        async function saveMitraInline(idPengajuan) {
            const select = document.getElementById(`select-mitra-${idPengajuan}`);
            const idMitra = select.value;

            if (!idMitra) {
                alert('Mohon pilih perusahaan terlebih dahulu!');
                return;
            }

            const cell = document.getElementById(`mitra-cell-${idPengajuan}`);
            const original = cell.innerHTML;
            cell.innerHTML = '<span class="text-gray-500 text-sm">Menyimpan...</span>';

            try {
                const res = await fetch('{{ route("staf-prodi.data_mahasiswa.update_mitra") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_pengajuan: idPengajuan, id_mitra: idMitra })
                });
                const result = await res.json();
                if (result.success) {
                    cell.innerHTML = `<span class="text-gray-900 font-medium">${result.data.nama_perusahaan}</span>`;
                    alert('Perusahaan berhasil ditentukan!');
                } else {
                    cell.innerHTML = original;
                    alert(result.message || 'Gagal memperbarui perusahaan.');
                }
            } catch (err) {
                cell.innerHTML = original;
                console.error(err);
                alert('Terjadi kesalahan: ' + err.message);
            }
        }

        // ===== MODAL MITRA =====
        function toggleMitraEdit(isEdit) {
            document.getElementById('mitraViewMode').classList.toggle('hidden', isEdit);
            document.getElementById('mitraEditMode').classList.toggle('hidden', !isEdit);
        }

        async function saveMitraModal() {
            const idMitra = document.getElementById('selectMitra').value;
            try {
                const res = await fetch('{{ route("staf-prodi.data_mahasiswa.update_mitra") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id_pengajuan: currentData.idPengajuan, id_mitra: idMitra })
                });
                const result = await res.json();
                if (result.success) {
                    document.getElementById('profilMitra').textContent = result.data.nama_perusahaan;
                    toggleMitraEdit(false);
                    alert('Perusahaan berhasil diperbarui!');
                } else {
                    alert(result.message || 'Gagal memperbarui perusahaan.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan');
            }
        }

        // ===== MODAL PROFIL FUNCTIONS =====
async function openProfilModal(npm) {
    try {
        const response = await fetch(`{{ url('data_mahasiswa') }}/${npm}/detail`);
        const result = await response.json();

        console.log('🔍 Response dari server:', result);
        console.log('📅 Periode Magang:', result.data?.periode_magang);

        if (!result.success) {
            alert(result.message || 'Gagal memuat data mahasiswa.');
            return;
        }

        const data = result.data;
        
        document.getElementById('profilNama').textContent = data.nama_lengkap || '-';
        document.getElementById('profilNPM').textContent = data.npm || '-';
        document.getElementById('profilKelas').textContent = data.kelas || '-';
        document.getElementById('profilIPK').textContent = data.ipk || '-';
        document.getElementById('profilHP').textContent = data.no_hp || '-';
        document.getElementById('profilMitra').textContent = data.perusahaan || '-';
        document.getElementById('profilPerusahaan').textContent = data.perusahaan || '-';
        document.getElementById('profilDosen').textContent = data.dosen_pembimbing || '-';
        document.getElementById('profilNilaiAkhir').textContent = data.nilai_akhir || '-';
        document.getElementById('profilPeriode').textContent = data.periode_magang || '-';
        
        currentData.idPengajuan = data.id_pengajuan;
        currentData.idDosen = data.id_dosen;
        currentData.nilaiAkhir = data.nilai_akhir;

        const baseUrl = '{{ url("data_mahasiswa") }}';
        const dokumenList = document.getElementById('dokumenList');
        dokumenList.innerHTML = generateDokumenHTML(data, baseUrl);

        toggleDosenEdit(false);

        document.getElementById('profilModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';

    } catch (error) {
        console.error(' Error loading profile:', error);
        alert('Terjadi kesalahan saat memuat data mahasiswa: ' + error.message);
    }
}

        function generateDokumenHTML(data, baseUrl) {
            const dokumen = [
                { label: 'KRS', url: data.krs_url, jenis: 'krs', size: '245 KB' },
                { label: 'Transkrip Nilai', url: data.transkrip_url, jenis: 'transkrip', size: '156 KB' },
                { label: 'Surat Pengantar', url: data.surat_pengantar_mahasiswa, jenis: 'surat', size: '89 KB' },
                { label: 'laporan mingguan', url: data.laporan_mingguan_url, jenis: 'laporan_mingguan', size: '512 KB' },
                { label: 'Laporan Akhir', url: data.laporan_akhir_url, jenis: 'laporan_akhir', size: '678 KB' }
            ];

            return dokumen.map((doc) => {
                const buttons = doc.url 
                    ? `
                        <div class="flex items-center gap-2">
                            <button onclick="window.open('${doc.url}', '_blank')" 
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                Lihat
                            </button>
                            <a href="${baseUrl}/${data.id_pengajuan}/download/${doc.jenis}" 
                                class="text-gray-600 hover:text-gray-800 p-1.5 hover:bg-gray-100 rounded-lg transition"
                                title="Unduh ${doc.label}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-
                                                                    linejoin="round"
                                    d="M12 4v12m0 0l-3-3m3 3l3-3M6 20h12" />
                                </svg>
                            </a>
                        </div>
                    `
                    : `<span class="text-xs text-gray-400">Tidak tersedia</span>`;

                return `
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">${doc.label}</p>
                            <p class="text-xs text-gray-500">${doc.size}</p>
                        </div>
                        ${buttons}
                    </div>
                `;
            }).join('');
        }

        function closeProfilModal() {
            document.getElementById('profilModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // ===== DOSEN EDIT (MODAL PROFIL) =====
        function toggleDosenEdit(isEdit) {
            document.getElementById('dosenViewMode').classList.toggle('hidden', isEdit);
            document.getElementById('dosenEditMode').classList.toggle('hidden', !isEdit);
        }

        async function saveDosenPembimbing() {
            const idDosen = document.getElementById('selectDosen').value;

            try {
                const response = await fetch('{{ route("staf-prodi.data_mahasiswa.update_dosen") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_pengajuan: currentData.idPengajuan,
                        id_dosen: idDosen
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('profilDosen').textContent = result.data.nama_dosen;
                    toggleDosenEdit(false);
                    alert('Dosen pembimbing berhasil diperbarui');
                } else {
                    alert(result.message || 'Gagal memperbarui dosen');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan');
            }
        }

        // ===== NILAI AKHIR =====
        function toggleNilaiEdit(isEdit) {
            document.getElementById('nilaiViewMode').classList.toggle('hidden', isEdit);
            document.getElementById('nilaiEditMode').classList.toggle('hidden', !isEdit);
        }

        async function saveNilaiAkhir() {
            const nilai = document.getElementById('inputNilaiAkhir').value;

            if (!nilai) {
                alert('Nilai tidak boleh kosong');
                return;
            }

            try {
                const response = await fetch('{{ route("staf-prodi.data_mahasiswa.update_nilai") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_pengajuan: currentData.idPengajuan,
                        nilai_akhir: nilai
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('profilNilaiAkhir').textContent = nilai;
                    toggleNilaiEdit(false);
                    alert('Nilai akhir berhasil disimpan');
                } else {
                    alert(result.message || 'Gagal menyimpan nilai');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan');
            }
        }

        // ===== SET MATKUL ELIGIBLE MODAL =====
        function openSetMatkulEligibleModal(idPengajuan, nama, npm) {
            document.getElementById('modalIdPengajuan').value = idPengajuan;
            document.getElementById('modalNamaMhs').textContent = nama;
            document.getElementById('modalNpmMhs').textContent = npm;

            document.getElementById('setMatkulEligibleModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSetMatkulEligibleModal() {
            document.getElementById('setMatkulEligibleModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // ===== MATA KULIAH ROW =====
        function addMatkulRow() {
            const container = document.getElementById('matkulRowsContainer');

            const row = document.createElement('div');
            row.className = 'matkul-row border border-gray-300 rounded-lg p-4 bg-gray-50';

            row.innerHTML = `
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-3">
                        <input type="text" name="mata_kuliah[${matkulRowCounter}][kode]" required
                            class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                    <div class="col-span-6">
                        <input type="text" name="mata_kuliah[${matkulRowCounter}][nama]" required
                            class="w-full px-3 py-2 text-sm border rounded-lg">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="mata_kuliah[${matkulRowCounter}][sks]" required min="1"
                            class="w-full px-3 py-2 text-sm border rounded-lg matkul-sks"
                            onchange="calculateTotalSks()">
                    </div>
                    <div class="col-span-1 flex items-end">
                        <button type="button" onclick="removeMatkulRow(this)"
                            class="w-full px-2 py-2 text-red-600">
                            ✕
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(row);
            matkulRowCounter++;
        }

        function removeMatkulRow(button) {
            button.closest('.matkul-row').remove();
            calculateTotalSks();
        }

        function calculateTotalSks() {
            let total = 0;
            document.querySelectorAll('.matkul-sks').forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.getElementById('totalSksEligible').textContent = total + ' SKS';
        }
    </script>
@endsection