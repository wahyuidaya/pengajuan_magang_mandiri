@extends('layouts.app_staf-prodi', ['activeMenu' => 'data_dosen_pembimbing', 'title' => 'Dashboard Staf Prodi'])

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Data Dosen Pembimbing</h1>
            <button
                onclick="openTambahDosenModal()"
                class="flex items-center space-x-2 px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Dosen</span>
            </button>
        </div>

        {{-- FORM SEARCH SESUAI GAMBAR --}}
        <form method="GET" action="{{ route('staf-prodi.data_dosen_pembimbing') }}">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama dosen..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
                </div>
                
                {{-- Tombol Cari Ikon Kaca Pembesar --}}
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

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-[#690dac]">
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                                Nama Dosen</th>
                            <th class="text-white text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">Jumlah Mahasiswa</th>
                            <th class="text-white text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dosenList as $dosen)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dosen->nama_dosen }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm bg-blue-100 text-blue-700">
                                    {{ $dosen->jumlah_mahasiswa ?? 0 }} Mahasiswa
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-center">
                                <button onclick="openProfilDosenModal('{{ $dosen->id_dosen }}')"
                                    title="Lihat Detail" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data dosen pembimbing
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                        {{-- PAGINATION SESUAI GAMBAR --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                {{-- Info Halaman (Kiri) --}}
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $dosenList->firstItem() ?? 0 }} sampai {{ $dosenList->lastItem() ?? 0 }} dari total {{ $dosenList->total() }} data
                        </p>
                    </div>

                    {{-- Navigasi (Kanan) --}}
                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            {{-- Sebelumnya --}}
                            @if ($dosenList->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $dosenList->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            {{-- Kotak Angka --}}
                            <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $dosenList->currentPage() }}
                            </div>

                            {{-- Berikutnya --}}
                            @if ($dosenList->hasMorePages())
                                <a href="{{ $dosenList->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                            @else
                                <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        

        {{-- MODAL PROFIL DOSEN --}}
        <div id="profilDosenModal" class="fixed inset-0 z-50 flex items-start justify-center pt-10 px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl relative animate-fade-in max-h-[90vh] overflow-y-auto">
                <div class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between sticky top-0 z-10">
                    <h2 class="text-base font-bold">Profil Dosen Pembimbing</h2>
                    <button onclick="closeProfilDosenModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi Dosen</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Nama Dosen</p>
                                <p id="dosenNama" class="font-medium text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Jumlah Mahasiswa Bimbingan</p>
                                <p id="dosenJumlah" class="font-medium text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Daftar Mahasiswa Bimbingan</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">NPM</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Perusahaan</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="mahasiswaList" class="divide-y divide-gray-100">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH DOSEN --}}
        <div id="tambahDosenModal" class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="display: none; background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative animate-fade-in">
                <div class="bg-purple-700 text-white px-5 py-3.5 rounded-t-xl flex items-center justify-between">
                    <h2 class="text-base font-bold">Tambah Dosen Pembimbing</h2>
                    <button onclick="closeTambahDosenModal()" class="text-white hover:text-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('staf-prodi.data_dosen_pembimbing.store') }}" method="POST" class="p-5">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen Pembimbing</label>
                            <input type="text" name="nama_dosen" required maxlength="255"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Masukkan Nama Dosen Pembimbing">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required maxlength="255"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Masukkan Email Dosen Pembimbing">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeTambahDosenModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.2s ease-out; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
        window.alert(@json(session('success')));
    @endif

    @if(session('error'))
        window.alert(@json(session('error')));
    @endif
});


        function openProfilDosenModal(idDosen) {
            document.getElementById('profilDosenModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';

            document.getElementById('dosenNama').textContent = 'Memuat...';
            document.getElementById('dosenJumlah').textContent = '-';
            document.getElementById('mahasiswaList').innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Memuat data...</td></tr>';

            fetch(`/data_dosen_pembimbing/${idDosen}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('dosenNama').textContent = data.data.nama;
                        document.getElementById('dosenJumlah').textContent = data.data.jumlah_mahasiswa + ' Mahasiswa';

                        const mahasiswaList = document.getElementById('mahasiswaList');
                        mahasiswaList.innerHTML = '';

                        if (data.data.mahasiswa_list.length === 0) {
                            mahasiswaList.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada mahasiswa bimbingan</td></tr>';
                        } else {
                            data.data.mahasiswa_list.forEach(mhs => {
                                let statusClass = '';
                                if (mhs.status === 'Disetujui') statusClass = 'bg-green-100 text-green-700';
                                else if (mhs.status === 'Diproses') statusClass = 'bg-yellow-100 text-yellow-700';
                                
                                const row = `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">${mhs.nama}</td>
                                        <td class="px-4 py-3 text-gray-700">${mhs.npm}</td>
                                        <td class="px-4 py-3 text-gray-700">${mhs.perusahaan}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">${mhs.status}</span>
                                        </td>
                                    </tr>`;
                                mahasiswaList.insertAdjacentHTML('beforeend', row);
                            });
                        }
                    }
                })
                .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan saat memuat data'); closeProfilDosenModal(); });
        }

        function closeProfilDosenModal() { document.getElementById('profilDosenModal').style.display = 'none'; document.body.style.overflow = 'auto'; }
        function openTambahDosenModal() { document.getElementById('tambahDosenModal').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        function closeTambahDosenModal() { document.getElementById('tambahDosenModal').style.display = 'none'; document.body.style.overflow = 'auto'; }

document.getElementById('profilDosenModal')?.addEventListener('click', function(e) {
    if(e.target === this) closeProfilDosenModal();
});

document.getElementById('tambahDosenModal')?.addEventListener('click', function(e) {
    if(e.target === this) closeTambahDosenModal();
});

        document.addEventListener('keydown', e => { if(e.key==='Escape'){ closeProfilDosenModal(); closeTambahDosenModal(); } });
    </script>
@endsection