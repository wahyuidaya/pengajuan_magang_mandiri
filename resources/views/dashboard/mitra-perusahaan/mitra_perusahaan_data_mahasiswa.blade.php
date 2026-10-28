@extends('layouts.app_mitra-perusahaan', ['activeMenu' => 'mitra_perusahaan_data_mahasiswa', 'title' => 'Data Mahasiswa Magang'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Mahasiswa Magang</h1>
        <form method="GET" action="{{ route('mitra-perusahaan.mitra_perusahaan_data_mahasiswa') }}">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#690dac]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">NPM</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Periode Magang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Posisi di
                            Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($mahasiswaMagang as $mahasiswa)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $mahasiswa['nama'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $mahasiswa['npm'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div id="periode-cell-{{ $mahasiswa['id_pengajuan'] }}">
                                    @if($mahasiswa['periode'])
                                        <span class="text-gray-900 font-medium">
                                            {{ $mahasiswa['periode'] }}
                                        </span>
                                    @else
                                        <button onclick="openPeriodeDropdown('{{ $mahasiswa['id_pengajuan'] }}')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium
                                                                    text-white bg-purple-600 hover:bg-purple-700
                                                                    rounded-lg transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tentukan Periode
                                        </button>
                                    @endif
                                </div>

                                <!-- Dropdown Periode -->
                                <div id="periode-dropdown-{{ $mahasiswa['id_pengajuan'] }}"
                                    class="hidden absolute z-10 mt-2 bg-white p-4 rounded-xl shadow-xl border border-gray-200 w-64">
                                    <div class="space-y-3">
                                        <div class="flex flex-col gap-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Mulai</label>
                                            <input id="mulai-{{ $mahasiswa['id_pengajuan'] }}" type="date"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Selesai</label>
                                            <input id="selesai-{{ $mahasiswa['id_pengajuan'] }}" type="date"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mt-4">
                                        <button onclick="savePeriodeInline('{{ $mahasiswa['id_pengajuan'] }}')"
                                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg text-xs font-bold transition">Simpan</button>
                                        <button onclick="closePeriodeDropdown('{{ $mahasiswa['id_pengajuan'] }}')"
                                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 rounded-lg text-xs font-bold transition">Batal</button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $mahasiswa['posisi'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full 
                                                {{ $mahasiswa['status'] === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $mahasiswa['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openModal(
                                                    '{{ $mahasiswa['nama'] }}',
                                                    '{{ $mahasiswa['npm'] }}',
                                                    '{{ $mahasiswa['periode'] }}',
                                                    '{{ $mahasiswa['posisi'] }}',
                                                    '{{ $mahasiswa['status'] }}',
                                                    '{{ $mahasiswa['prodi'] }}',
                                                    '{{ $mahasiswa['foto_url'] }}',
                                                    '{{ $mahasiswa['id_pengajuan'] }}'
                                                )"
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
                                Belum ada data mahasiswa magang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- PAGINATION --}}
            <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between items-center sm:hidden">
                    @if ($mahasiswaMagang->onFirstPage())
                        <span class="text-sm text-gray-400">Sebelumnya</span>
                    @else
                        <a href="{{ $mahasiswaMagang->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                    @endif

                    @if ($mahasiswaMagang->hasMorePages())
                        <a href="{{ $mahasiswaMagang->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                    @else
                        <span class="text-sm text-gray-400">Berikutnya</span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Menampilkan {{ $mahasiswaMagang->firstItem() ?? 0 }} sampai
                            {{ $mahasiswaMagang->lastItem() ?? 0 }} dari total {{ $mahasiswaMagang->total() }} data
                        </p>
                    </div>

                    <div>
                        <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                            @if ($mahasiswaMagang->onFirstPage())
                                <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                            @else
                                <a href="{{ $mahasiswaMagang->previousPageUrl() }}"
                                    class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                            @endif

                            <div
                                class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm">
                                {{ $mahasiswaMagang->currentPage() }}
                            </div>

                            @if ($mahasiswaMagang->hasMorePages())
                                <a href="{{ $mahasiswaMagang->nextPageUrl() }}"
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
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full relative animate-fade-in">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">Detail Mahasiswa</h2>
            </div>
            <div class="p-4">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-violet-100 bg-gray-200 flex items-center justify-center">

                        <img id="modalFoto" src="" class="w-full h-full object-cover hidden">

                        <svg id="modalFotoDefault" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="text-center mb-4">
                    <h3 id="modalNama" class="text-xl font-bold text-gray-900 mb-1">-</h3>
                    <p id="modalNPM" class="text-gray-600 text-sm font-medium">-</p>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-600 mb-2">Periode Magang</p>

                        <!-- View Mode -->
                        <div id="periodeViewMode" class="flex items-center justify-between">
                            <p id="profilPeriode" class="text-sm font-medium text-gray-900">-</p>
                            <button onclick="togglePeriodeEdit(true)" class="text-blue-600 hover:text-blue-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                    a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Edit Mode -->
                        <div id="periodeEditMode" class="hidden">
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-[10px] text-gray-500">TANGGAL MULAI</label>
                                    <input id="inputMulaiModal" type="date"
                                        class="w-full px-3 py-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] text-gray-500">TANGGAL SELESAI</label>
                                    <input id="inputSelesaiModal" type="date"
                                        class="w-full px-3 py-2 border rounded-lg text-sm">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="savePeriode()"
                                    class="flex-1 bg-purple-700 text-white py-1.5 rounded-lg text-xs">Simpan</button>
                                <button onclick="togglePeriodeEdit(false)"
                                    class="flex-1 bg-gray-100 text-gray-700 py-1.5 rounded-lg text-xs">Batal</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Posisi di Perusahaan
                        </p>
                        <p id="modalPosisi" class="text-sm font-medium text-gray-900">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Status</p>
                        <span id="modalStatus"
                            class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full">-</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Fakultas/Program</p>
                        <p id="modalProdi" class="text-sm font-medium text-gray-900">-</p>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200">
                <button onclick="closeModal()"
                    class="w-full bg-violet-700 hover:bg-violet-800 text-white font-semibold py-2.5 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg">
                    Tutup
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
        /* =====================================================
           GLOBAL STATE
        ===================================================== */
        let activePengajuanId = null;

        /* =====================================================
           MODAL DETAIL MAHASISWA
        ===================================================== */
function openModal(nama, npm, periode, posisi, status, prodi, fotoUrl, idPengajuan) {
    activePengajuanId = idPengajuan;

    document.getElementById('modalNama').textContent = nama;
    document.getElementById('modalNPM').textContent = npm;
    document.getElementById('profilPeriode').textContent = periode || '-';

    // Perbaikan: Hapus baris 'inputPeriodeMagang' yang menyebabkan error
    // Jika ingin mengisi input tanggal di dalam modal secara otomatis:
    if (periode && periode.includes(' - ')) {
        const dates = periode.split(' - ');
        document.getElementById('inputMulaiModal').value = dates[0];
        document.getElementById('inputSelesaiModal').value = dates[1];
    }

    document.getElementById('modalPosisi').textContent = posisi;
    document.getElementById('modalProdi').textContent = prodi;

    const img = document.getElementById('modalFoto');
    const icon = document.getElementById('modalFotoDefault');

    if (fotoUrl && fotoUrl !== '') {
        img.src = fotoUrl.startsWith('http') ? fotoUrl : `/storage/${fotoUrl}`;
        img.classList.remove('hidden');
        icon.classList.add('hidden');
    } else {
        img.classList.add('hidden');
        icon.classList.remove('hidden');
    }

    const statusElement = document.getElementById('modalStatus');
    statusElement.textContent = status;
    statusElement.className = status === 'Aktif'
        ? 'inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800'
        : 'inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-gray-200 text-gray-700';

    document.getElementById('detailModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.getElementById('detailModal')?.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        /* =====================================================
           SEARCH CLIENT SIDE
        ===================================================== */
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const keyword = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const nama = row.cells[0]?.textContent.toLowerCase() || '';
                    const npm = row.cells[1]?.textContent.toLowerCase() || '';
                    row.style.display =
                        nama.includes(keyword) || npm.includes(keyword) ? '' : 'none';
                });
            });
        }

        /* =====================================================
           HELPER ALERT
        ===================================================== */
        function showAlert(msg, success = true) {
            alert(msg);
        }

        /* =====================================================
           PERIODE MAGANG – INLINE (DOSPEM STYLE)
        ===================================================== */
        function openPeriodeDropdown(idPengajuan) {
            document.getElementById(`periode-dropdown-${idPengajuan}`)?.classList.remove('hidden');
        }

        function closePeriodeDropdown(idPengajuan) {
            document.getElementById(`periode-dropdown-${idPengajuan}`)?.classList.add('hidden');
        }

async function savePeriodeInline(idPengajuan) {
    const mulai = document.getElementById(`mulai-${idPengajuan}`).value;
    const selesai = document.getElementById(`selesai-${idPengajuan}`).value;

    if (!mulai || !selesai) {
        alert('Tanggal mulai dan selesai harus diisi');
        return;
    }

    const payload = {
        id_pengajuan: idPengajuan,
        tanggal_mulai: mulai,
        tanggal_selesai: selesai
    };

    try {
        const res = await fetch('{{ route("mitra.data_mahasiswa.update_periode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        
        if (result.success) {
            // Tampilkan alert SEBELUM reload
            alert('Periode berhasil diperbarui'); 
            
            // Baru kemudian reload untuk memperbarui tampilan tabel
            location.reload(); 
        } else {
            alert(result.message || 'Gagal memperbarui periode');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan koneksi ke server');
    }
}

        /* =====================================================
           PERIODE MAGANG – MODAL (CARD STYLE)
        ===================================================== */
        function togglePeriodeEdit(isEdit) {
            document.getElementById('periodeViewMode')?.classList.toggle('hidden', isEdit);
            document.getElementById('periodeEditMode')?.classList.toggle('hidden', !isEdit);
        }

async function savePeriode() {
    // Ambil value dari input date di modal
    const mulai = document.getElementById('inputMulaiModal').value;
    const selesai = document.getElementById('inputSelesaiModal').value;

    if (!mulai || !selesai) {
        alert('Tanggal mulai dan selesai harus diisi');
        return;
    }

    const payload = {
        id_pengajuan: activePengajuanId, // Menggunakan global state yang diisi saat openModal
        tanggal_mulai: mulai,
        tanggal_selesai: selesai
    };

    try {
        const res = await fetch('{{ route("mitra.data_mahasiswa.update_periode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (result.success) {
            // Update tampilan teks di modal tanpa reload (opsional)
            document.getElementById('profilPeriode').textContent = result.periode_tampil;
            togglePeriodeEdit(false);
            
            // Beri notifikasi dan reload untuk sinkronisasi data tabel
            alert('Periode berhasil diperbarui');
            location.reload(); 
        } else {
            alert(result.message || 'Gagal memperbarui periode');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan server');
    }
}
    </script>


@endsection