@extends('layouts.app_mahasiswa', ['activeMenu' => 'status_pengajuan', 'title' => 'Status Pengajuan'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Status Pengajuan</h1>

        @if(isset($pengajuanList))
            @foreach($pengajuanList as $pengajuan)
                @if($pengajuan->status === 'disetujui' && !$pengajuan->silabus_url)
                    <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-800 rounded-lg">
                        <p class="font-semibold mb-1">
                            Pengajuan Magang Disetujui
                        </p>
                        <p class="text-sm">
                            Pengajuan magang Anda telah disetujui oleh <b>Staf Program Studi</b>.
                            Silakan melanjutkan ke tahap berikutnya dengan mengunggah
                            <b>silabus dari mitra magang</b>.
                        </p>
                    </div>
                    @break
                @endif
            @endforeach
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-[#690dac] text-white">
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                            ID Pengajuan
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Perusahaan
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        /**
                         * @var \App\Models\PengajuanMagang[] $pengajuanList
                         * @var \App\Models\PengajuanMagang $pengajuan
                         */
                    @endphp
                    @forelse($pengajuanList ?? [] as $pengajuan)
                        @php
                            // Extract relasi dengan null check eksplisit
                            $mitra = (isset($pengajuan->mitra) && $pengajuan->mitra !== null) ? $pengajuan->mitra : null;
                            $namaPerusahaan = ($mitra !== null && isset($mitra->nama_perusahaan)) ? $mitra->nama_perusahaan : '-';

                            // Extract data pengajuan dengan isset
                            $idPengajuan = (isset($pengajuan->id_pengajuan) && $pengajuan->id_pengajuan !== null) ? $pengajuan->id_pengajuan : '';

                            // Tanggal dengan check method exists
                            if (isset($pengajuan->tanggal_pengajuan) && is_object($pengajuan->tanggal_pengajuan) && method_exists($pengajuan->tanggal_pengajuan, 'format')) {
                                $tanggal = $pengajuan->tanggal_pengajuan->format('Y-m-d');
                            } else {
                                $tanggal = '-';
                            }

                            $status = (isset($pengajuan->status) && $pengajuan->status !== null) ? $pengajuan->status : 'draft';
                            $catatan = (isset($pengajuan->catatan_verifikator) && $pengajuan->catatan_verifikator !== null) ? $pengajuan->catatan_verifikator : '-';
                            $silabusUrl = (isset($pengajuan->silabus_url) && $pengajuan->silabus_url !== null) ? $pengajuan->silabus_url : null;

                            // Status badge
                            $statusBadges = [
                                'draft' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>',
                                'diajukan' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diajukan</span>',
                                'verifikasi' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Diproses</span>',
                                'revisi' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Revisi</span>',
                                'disetujui' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>',
                                'ditolak' => '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
                            ];
                            $statusBadge = $statusBadges[$status] ?? '<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">' . ucfirst($status) . '</span>';

                            // Keterangan default
                            $keteranganDefault = [
                                'draft' => 'Pengajuan masih dalam draft',
                                'diajukan' => 'Menunggu verifikasi staf prodi',
                                'verifikasi' => 'Menunggu verifikasi dokumen',
                                'revisi' => $catatan,
                                'disetujui' => 'Pengajuan disetujui oleh staf prodi',
                                'ditolak' => $catatan,
                            ];
                            $keterangan = $catatan !== '-' ? $catatan : ($keteranganDefault[$status] ?? '-');
                        @endphp
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                {{ substr($idPengajuan, 0, 8) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $tanggal }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $namaPerusahaan }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                {!! $statusBadge !!}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $keterangan }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($status === 'disetujui')
                                    <div class="flex flex-col gap-2">
                                        {{-- SILABUS --}}
                                        @if($silabusUrl)
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('mahasiswa.status_pengajuan.download_silabus', $idPengajuan) }}"
                                                    class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-lg bg-green-500 text-white hover:bg-green-600 transition duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>

                                                <button
                                                    onclick="openUploadSilabusModal('{{ $idPengajuan }}', '{{ addslashes($namaPerusahaan) }}', true)"
                                                    class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    Ganti
                                                </button>
                                            </div>
                                        @else
                                            <button
                                                onclick="openUploadSilabusModal('{{ $idPengajuan }}', '{{ addslashes($namaPerusahaan) }}')"
                                                class="px-2.5 py-1 inline-flex items-center justify-center text-xs leading-5 font-semibold rounded-lg bg-[#690dac] text-white hover:bg-purple-800 transition duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Upload Silabus
                                            </button>
                                        @endif

                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Belum ada pengajuan magang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Upload Silabus --}}
    <div id="uploadSilabusModal" class="fixed inset-0 z-50 overflow-y-auto hidden"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full animate-modal">
                <form id="uploadSilabusForm" action="{{ route('mahasiswa.status_pengajuan.upload_silabus') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="modalIdPengajuan" name="id_pengajuan">
                    <input type="hidden" id="isUpdateSilabus" name="is_update" value="0">
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
                                Perusahaan: <span id="modalPerusahaan" class="font-semibold text-gray-900"></span>
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
                                    accept=".pdf,.docx">
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
                alert(" Berhasil: {{ session('success') }}");
            @endif

            @if(session('error'))
                alert(" Error: {{ session('error') }}");
            @endif
            // -------------------------

            function qs(id) {
                return document.getElementById(id);
            }

            function qs(id) {
                return document.getElementById(id);
            }

            /* =============================
               MODAL UPLOAD SILABUS
            ============================== */
            window.openUploadSilabusModal = function (id, perusahaan, isUpdate = false) {
                qs('modalIdPengajuan').value = id;
                qs('modalPerusahaan').textContent = perusahaan;
                qs('isUpdateSilabus').value = isUpdate ? '1' : '0';

                qs('uploadSilabusModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeUploadSilabusModal = function () {
                qs('uploadSilabusModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                qs('uploadSilabusForm').reset();

                const selectedFileName = qs('selectedFileName');
                if (selectedFileName) {
                    selectedFileName.classList.add('hidden');
                }
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

        });
    </script>
@endsection