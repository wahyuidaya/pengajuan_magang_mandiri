@extends('layouts.app_mahasiswa', ['activeMenu' => 'laporan', 'title' => 'Laporan Magang'])

@php
    $currentTab = request()->query('tab', 'mingguan');
    $baseUrl = route('mahasiswa.laporan');
    
    // 1. Logika Cek Status Non-Aktif (Prioritas Utama)
    // Mengecek apakah status di tabel pengajuan adalah 'non_aktif'
    $isStatusNonAktif = $pengajuan && $pengajuan->status === 'non_aktif';

    // 2. Logika Cek Approval (Hanya berlaku jika tidak sedang non-aktif)
    $isNotApproved = !$pengajuan || $pengajuan->status !== 'disetujui';
@endphp

@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Magang</h1>

{{-- ============================ LEVEL 1: TAMPILAN NON-AKTIF ============================ --}}
@if($isStatusNonAktif)
    <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden mb-8 transition-all">
        <div class="p-16 text-center">
            {{-- Ikon Gembok Terkunci --}}
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 rounded-full mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            {{-- Judul --}}
            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Status Magang Anda Dinonaktifkan</h2>
            
            {{-- Deskripsi Utama --}}
            <div class="text-gray-600 mx-auto leading-relaxed mb-6" style="max-width: 550px;">
                @if(isset($pesanLock))
                    <p class="font-medium text-red-700">{{ $pesanLock }}</p>
                @else
                    <p>
                        <strong>Anda telah dinonaktifkan</strong> dari program magang ini dikarenakan kelalaian tidak mengunggah laporan mingguan sebanyak <strong>4 kali</strong>. 
                    </p>
                @endif
            </div>

            {{-- Badge Status --}}
            <div class="mb-8">
                <div class="inline-block px-8 py-2 bg-red-600 text-white text-xs font-black rounded-full shadow-lg tracking-widest uppercase">
                    Status: Non-Aktif
                </div>
            </div>

            {{-- Instruksi Aktivasi Kembali --}}
            <div class="pt-6 border-t border-gray-100 mx-auto" style="max-width: 500px;">
                <p class="text-sm text-gray-500 font-medium leading-relaxed">
                    Jika menurut Anda ini adalah kesalahan teknis atau Anda ingin mengajukan permohonan aktivasi kembali, 
                    segera hubungi Staf Program Studi atau Koordinator Magang untuk verifikasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>

        {{-- ============================ LEVEL 2: TAMPILAN BELUM DISETUJUI ============================ --}}
        @elseif($isNotApproved)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden p-16 transition-all">
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-50 rounded-full mb-6">
                        <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Akses Laporan Terkunci</h3>
                    <p class="text-gray-500 max-w-sm mx-auto leading-relaxed mb-8">
                        Fitur pengisian laporan belum dapat diakses. Pastikan Anda sudah melakukan pengajuan magang dan status Anda telah <b>Disetujui</b> oleh Program Studi.
                    </p>
                    <div class="flex gap-4">
                        <div class="px-6 py-2 bg-gray-50 text-gray-500 text-sm font-semibold rounded-xl border border-gray-200">
                            Status Saat Ini: <span class="text-orange-600 font-bold">
                                {{ $pengajuan ? ucfirst($pengajuan->status) : 'Belum Ada Pengajuan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        {{-- ============================ LEVEL 3: TAMPILAN FULL KONTEN (DISETUJUI) ============================ --}}
        @else
            {{-- Info Mahasiswa --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="relative p-6">
                <div class="absolute top-0 bottom-0 left-0 w-1 bg-violet-700 rounded-tl-xl"></div>
                <div class="grid grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Nama Mahasiswa</p>
                        <p class="font-bold text-gray-800">{{ $mahasiswa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">NPM</p>
                        <p class="font-bold text-gray-800">{{ $mahasiswa->npm }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">IPK</p>
                        <p class="font-bold text-gray-800">{{ number_format($mahasiswa->ipk, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status Kelayakan</p>
                        @if ($pengajuan)
                            <p class="font-bold {{ $pengajuan->status === 'disetujui' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $pengajuan->status === 'disetujui' ? 'Layak' : 'Belum' }}
                            </p>
                        @else
                            <p class="font-bold text-gray-400">Belum Ada Pengajuan Disetujui</p>
                        @endif

                    </div>
                </div>
            </div>
            <div class="relative p-4 bg-blue-50 border-t border-gray-200">
                <div class="absolute top-0 bottom-0 left-0 w-1 bg-blue-500 rounded-bl-xl"></div>
                <p class="flex items-center text-sm text-blue-700 font-semibold mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Dosen Pembimbing Magang
                </p>
                <p class="ml-7 text-sm text-gray-700">
    {{ optional($pengajuan?->dosenPembimbing)->nama_dosen ?? 'Belum Ditentukan' }}
</p>
            </div>
        </div>

            {{-- Tab Navigation --}}
        <div class="mb-8">
            <div class="flex space-x-2 mb-6">
                <a href="{{ $baseUrl }}?tab=mingguan"
                    class="px-5 py-2 rounded-lg font-semibold transition duration-150 shadow-sm border
                    {{ $currentTab === 'mingguan' ? 'bg-[#690dac] text-white border-[#690dac]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    Laporan Mingguan
                </a>
                <a href="{{ $baseUrl }}?tab=akhir"
                    class="px-5 py-2 rounded-lg font-semibold transition duration-150 shadow-sm border
                    {{ $currentTab === 'akhir' ? 'bg-[#690dac] text-white border-[#690dac]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    Laporan Akhir
                </a>
                <a href="{{ $baseUrl }}?tab=nilai_akhir"
                    class="px-5 py-2 rounded-lg font-semibold transition duration-150 shadow-sm border
                    {{ $currentTab === 'nilai_akhir' ? 'bg-[#690dac] text-white border-[#690dac]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    Nilai Akhir
                </a>
            </div>

{{-- Tab Content: Laporan Mingguan --}}
@if ($currentTab === 'mingguan')

<div>
    {{-- Form Upload --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Upload Laporan Mingguan
        </h2>

        <form action="{{ route('mahasiswa.laporan.upload-mingguan') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            {{-- Minggu --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Minggu ke-
                </label>
                <input type="number"
                       name="minggu_ke"
                       min="1"
                       max="52"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm"
                       placeholder="Contoh: 1">
                <p class="text-xs text-gray-500 mt-1">
                    Setiap minggu hanya boleh memiliki satu laporan.
                </p>
                @error('minggu_ke')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Laporan Mingguan
                </label>
                <input type="text"
                       name="judul_laporan"
                       value="{{ old('judul_laporan') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm"
                        placeholder="Masukkan judul laporan mingguan">
                @error('judul_laporan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Singkat
                </label>
                <textarea name="deskripsi"
                          rows="3"
                          placeholder="Ringkasan isi laporan atau hasil magang"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- File --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload File (PDF/DOCX)
                </label>
                <input type="file"
                       name="file_laporan"
                       accept=".pdf,.doc,.docx"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Maksimal ukuran file: 5MB
                </p>
                @error('file_laporan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 🔴 INFO NONAKTIF (POSISI JELAS DI SINI) --}}
            @if (($pengajuan->status_keaktifan ?? 'aktif') === 'nonaktif')
                <div class="text-sm text-red-600 font-semibold">
                    Status magang nonaktif karena tidak melapor 4 minggu berturut-turut.
                    Anda tidak dapat mengunggah laporan.
                </div>
            @endif

            {{-- BUTTON --}}
            <button type="submit"
                @if (($pengajuan->status_keaktifan ?? 'aktif') === 'nonaktif') disabled @endif
                class="w-full bg-[#690dac] text-white font-semibold py-3 px-4 rounded-lg
                       transition duration-150 shadow-md
                       disabled:opacity-50 disabled:cursor-not-allowed">
                Upload Laporan Mingguan
            </button>

        </form>
    </div>

                    {{-- Daftar Laporan --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 overflow-x-auto">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Daftar Laporan Mingguan</h2>
                        @if (isset($laporanMingguan) && $laporanMingguan->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Minggu ke</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul Laporan</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Upload</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                                    @foreach ($laporanMingguan as $index => $laporan)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap">Minggu ke-{{ $laporan->minggu_ke }}</td>
                                            <td class="px-3 py-4">{{ $laporan->judul_laporan }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($laporan->tanggal_upload)->format('d-m-Y') }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                @if (isset($laporan->status_badge))
                                                    {!! $laporan->status_badge !!}
                                                @else
                                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                                        {{ ucfirst($laporan->status ?? 'pending') }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-3 py-4 whitespace-nowrap flex space-x-2 text-xs">
                                                <span class="text-gray-400">|</span>
                                                <a href="{{ route('mahasiswa.laporan.download-mingguan', $laporan->id_laporan_mingguan) }}"
                                                    class="text-violet-600 hover:text-violet-900 font-semibold flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-0.5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 11.586V3a1 1 0 112 0v8.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>Unduh
                                                </a>
                                                @if ($laporan->status === 'pending')
                                                    <span class="text-gray-400">|</span>
                                                    <form
                                                        action="{{ route('mahasiswa.laporan.delete-mingguan', $laporan->id_laporan_mingguan)}}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 font-semibold flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-0.5"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-12">
    <div class="flex flex-col items-center text-center">
        <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>

        <h3 class="text-xl font-semibold text-gray-700 mb-2">
            Belum Ada Laporan Mingguan
        </h3>

        <p class="text-gray-500 mb-6 max-w-md">
            Anda belum mengunggah laporan mingguan.
            Silakan unggah laporan sesuai aktivitas magang setiap minggu.
        </p>
    </div>
</div>

                        @endif
                    </div>
                </div>
            @endif

            {{-- Tab Content: Laporan Akhir --}}
            @if ($currentTab === 'akhir')
                <div>
                    {{-- Form Upload --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Laporan Akhir</h2>
                        <form action="{{ route('mahasiswa.laporan.upload-akhir') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="judul_akhir" class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan
                                    Akhir</label>
                                <input type="text" name="judul_laporan" id="judul_akhir"
                                    placeholder="Masukkan judul laporan akhir" value="{{ old('judul_laporan') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm">
                                @error('judul_laporan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="deskripsi_akhir" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                                    Singkat</label>
                                <textarea name="deskripsi" id="deskripsi_akhir" rows="3"
                                    placeholder="Ringkasan isi laporan atau hasil magang"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="pt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload File (PDF/DOCX)</label>
                                <input type="file" name="file_laporan" id="file_laporan_akhir" accept=".pdf,.doc,.docx"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">Maksimal ukuran file: 10MB</p>
                                @error('file_laporan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-[#690dac] hover:bg-[#570992] text-white font-semibold py-3 px-4 rounded-lg transition duration-150 shadow-md">
                                Upload Laporan Akhir
                            </button>
                        </form>
                    </div>

                    {{-- Daftar Laporan Akhir --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 overflow-x-auto">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Daftar Laporan Akhir</h2>
                        @if (isset($laporanAkhir) && $laporanAkhir->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul Laporan</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Upload</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                                    @foreach ($laporanAkhir as $index => $laporan)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="px-3 py-4">{{ $laporan->judul_laporan }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                {{ $laporan->tanggal_upload->format('d-m-Y') }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap">{!! $laporan->status_badge !!}</td>
                                            <td class="px-3 py-4 whitespace-nowrap flex space-x-2 text-xs">
                                                <span class="text-gray-400">|</span>
                                                <a href="{{ route('mahasiswa.laporan.download-akhir', $laporan->id_laporan_akhir) }}"
                                                    class="text-violet-600 hover:text-violet-900 font-semibold flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-0.5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 11.586V3a1 1 0 112 0v8.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>Unduh
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-12">
    <div class="flex flex-col items-center text-center">
        <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>

        <h3 class="text-xl font-semibold text-gray-700 mb-2">
            Belum Ada Laporan Akhir
        </h3>

        <p class="text-gray-500 mb-6 max-w-md">
            Laporan akhir dapat diunggah setelah seluruh kegiatan magang selesai.
        </p>
    </div>
</div>

                        @endif
                    </div>
                </div>
            @endif

            {{-- Tab Content: Nilai Akhir --}}
            @if ($currentTab === 'nilai_akhir')
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Nilai Akhir Magang</h2>
                    @if (isset($nilaiAkhir))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-[#690dac] p-6 rounded-lg shadow-md text-white">
                                <p class="text-sm font-light uppercase opacity-80 mb-2">Nilai Akhir Kegiatan Magang</p>
                                <h3 class="text-8xl font-extrabold leading-none inline-block">
                                    {{ $nilaiAkhir->nilai_huruf }}
                                    <span class="text-2xl font-medium text-white opacity-70">({{ number_format($nilaiAkhir->nilai_angka, 2) }})</span>
                                </h3>
                                <div class="w-full border-t border-white border-opacity-20 my-4"></div>
                                <p class="mt-4 text-sm font-medium text-white text-opacity-70">Status: Tersedia</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex flex-col space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">TANGGAL PENETAPAN</p>
                                    <p class="font-semibold text-gray-800">{{ $nilaiAkhir->tanggal_penetapan 
                                        ? \Carbon\Carbon::parse($nilaiAkhir->tanggal_penetapan)->format('d-m-Y') 
                                        : '-' 
                                    }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">PERIODE MAGANG</p>
                                    <p class="font-semibold text-gray-800">{{ $nilaiAkhir->periode }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">PERUSAHAAN</p>
                                    <p class="font-semibold text-gray-800">{{ optional($pengajuan?->mitra)->nama_perusahaan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md mb-6">
                            <p class="text-xs italic">
                                <span class="font-semibold">Informasi:</span> Nilai di atas adalah hasil evaluasi dari Staff
                                Prodi berdasarkan penilaian dari mitra perusahaan dan berbagai aspek penilaian kegiatan magang
                                Anda. Nilai ini bersifat final dan akan digunakan untuk keperluan akademik.
                            </p>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Komponen Penilaian</h3>
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 space-y-3">
                            @php
                                $statusBadges = [
                                    'belum' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-300">Belum</span>',
                                    'terisi' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-300">Terisi</span>',
                                    'selesai' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-300">Selesai</span>',
                                ];
                            @endphp
                            <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-700 font-medium">Penilaian Mitra Perusahaan</p>
                                {!! $statusBadges[$nilaiAkhir->penilaian_mitra] ?? $statusBadges['belum'] !!}
                            </div>
                            <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-700 font-medium">Evaluasi Laporan Magang</p>
                                {!! $statusBadges[$nilaiAkhir->evaluasi_laporan] ?? $statusBadges['belum'] !!}
                            </div>
                        </div>
                    @else
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-12">
    <div class="flex flex-col items-center text-center">
        <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
        </svg>

        <h3 class="text-xl font-semibold text-gray-700 mb-2">
            Nilai Akhir Belum Tersedia
        </h3>

        <p class="text-gray-500 mb-6 max-w-md">
            Nilai akhir akan ditampilkan setelah proses evaluasi laporan
            dan penilaian dari mitra perusahaan selesai.
        </p>
    </div>
</div>

                    @endif
                </div>
            @endif
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session()->has('success')) alert("Berhasil: " + @json(session('success'))); @endif
            @if (session()->has('error')) alert("Error: " + @json(session('error'))); @endif
        });
    </script>
@endsection