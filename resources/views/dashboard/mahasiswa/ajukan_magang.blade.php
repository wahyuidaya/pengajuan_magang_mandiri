@extends('layouts.app_mahasiswa', ['activeMenu' => 'ajukan_magang', 'title' => 'Ajukan Magang'])

@section('content')

@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ajukan Magang</h1>
        <p class="text-gray-600 mb-6">Lengkapi formulir di bawah ini untuk mengajukan magang Anda</p>

        @php
            $pengajuanAktif = null;
            $pengajuanDitolak = null;
            $isNonAktif = false;

            if (isset($mahasiswa)) {
                // 1. Cek apakah mahasiswa sedang dinonaktifkan (Sanksi)
                $isNonAktif = \App\Models\PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                    ->where('status', 'non_aktif')
                    ->exists();

                // 2. Ambil data pengajuan aktif (diajukan/verifikasi/disetujui)
                $pengajuanAktif = \App\Models\PengajuanMagang::with('mitra')
                    ->where('id_mhs', $mahasiswa->id_mhs)
                    ->whereIn('status', ['diajukan', 'verifikasi', 'disetujui'])
                    ->first();

                // 3. Ambil pengajuan terakhir untuk cek status ditolak
                $pengajuanTerakhir = \App\Models\PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                    ->latest('tanggal_pengajuan')
                    ->first();

                // Kondisi Lock Ditolak: Jika status terakhir ditolak DAN tidak ada pengajuan aktif lainnya
                if ($pengajuanTerakhir && $pengajuanTerakhir->status === 'ditolak' && !$pengajuanAktif) {
                    $pengajuanDitolak = $pengajuanTerakhir;
                }

                // 4. Cek Kelengkapan Foto
                $fotoLengkap = $mahasiswa->foto_url;
                $tampilkanPeringatan = !$fotoLengkap;
            }
        @endphp

        {{-- KONDISI 1: LOCK KARENA SANKSI (NON-AKTIF) --}}
        @if($isNonAktif)
            <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden p-16 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 rounded-full mb-6 text-red-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Formulir Pengajuan Terkunci</h3>
                <p class="text-gray-500 max-w-lg mx-auto leading-relaxed mb-8">
                    Mohon maaf, Anda tidak dapat melakukan pengajuan magang baru karena status keanggotaan magang Anda saat ini
                    sedang <strong>Non-Aktif</strong>.
                </p>
                <div
                    class="inline-block px-8 py-3 bg-red-600 text-white text-sm font-black rounded-full shadow-lg tracking-widest uppercase">
                    Status: Sanksi Administratif
                </div>
                <p class="mt-8 text-sm text-gray-400 italic">
                    Silahkan hubungi Staf Program Studi untuk proses aktivasi kembali.
                </p>
            </div>

            {{-- KONDISI 2: LOCK KARENA DITOLAK (MODE LOCK BARU) --}}
        @elseif($pengajuanDitolak)
            <div class="bg-white rounded-2xl shadow-md border border-orange-100 overflow-hidden p-8 text-center transition-all">

                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-50 rounded-full mb-4 text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-1">Akses Pengajuan Terkunci</h3>
                <p class="text-sm text-gray-600 max-w-xl mx-auto mb-4">
                    Pengajuan magang Anda sebelumnya telah <strong>Ditolak</strong>.
                </p>

                @if($pengajuanDitolak->catatan_verifikator)
                    <div class="max-w-md mx-auto mb-5 p-3 bg-red-50 border border-red-100 rounded-lg text-left">
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Catatan Penolakan:</p>
                        <p class="text-sm text-red-800 italic leading-tight">"{{ $pengajuanDitolak->catatan_verifikator }}"</p>
                    </div>
                @endif

                <div
                    class="inline-block px-5 py-1.5 bg-orange-500 text-white text-[11px] font-black rounded-full shadow-md tracking-widest uppercase">
                    Status: Ditolak
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 max-w-md mx-auto">
                    <p class="text-xs text-gray-500 leading-normal">
                        Silakan hubungi <strong>Staf Program Studi</strong> untuk membuka kembali akses formulir Anda.
                    </p>
                </div>
            </div>

            {{-- KONDISI 3: TAMPILAN NORMAL (FORM) --}}
        @else
            {{-- TAMPILAN FORMULIR NORMAL --}}
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 w-full">

                @if($pengajuanDitolak && $pengajuanDitolak->catatan_verifikator)
                    <div class="p-4 mb-6 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg" role="alert">
                        <div class="flex items-start space-x-3">
                            <svg class="inline w-5 h-5 mt-0.5 flex-shrink-0.." fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <span class="font-semibold">Pengajuan Sebelumnya Ditolak</span>
                                <p class="mt-2 text-sm">
                                    <strong>Alasan Penolakan:</strong><br>
                                    {{ $pengajuanDitolak->catatan_verifikator }}
                                </p>
                                <p class="mt-3 text-sm font-medium italic underline">
                                    Silakan perbaiki dokumen atau data Anda sesuai catatan di atas sebelum mengajukan kembali.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($pengajuanAktif)
                    <div class="p-4 mb-6 text-sm text-blue-800 bg-blue-100 rounded-lg flex items-start space-x-3 border border-blue-200"
                        role="alert">
                        <svg class="inline w-5 h-5 mt-0.5 flex-shrink-0.." fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <span class="font-medium">Informasi:</span> Anda sudah memiliki pengajuan magang dengan status
                            <strong>{{ ucfirst($pengajuanAktif->status) }}</strong>.
                            <p class="mt-2">Silakan cek <a href="{{ route('mahasiswa.status_pengajuan') }}"
                                    class="font-semibold underline hover:text-blue-900">Status Pengajuan</a> untuk detail lebih
                                lanjut.</p>
                        </div>
                    </div>
                @else
                    @if($tampilkanPeringatan)
                        <div class="p-4 mb-6 text-sm text-yellow-800 bg-yellow-100 rounded-lg flex items-start space-x-3 border border-yellow-200"
                            role="alert">
                            <svg class="inline w-5 h-5 mt-0.5 flex-shrink-0.." fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <span class="font-bold">Peringatan:</span>
                                <p class="mt-1">Anda wajib mengunggah <strong>foto formal</strong> sebelum mengajukan magang.</p>
                                <p class="mt-2 text-sm font-semibold">
                                    Silakan unggah di <a href="{{ route('mahasiswa.profil_mahasiswa.index') }}"
                                        class="underline hover:text-yellow-900 text-purple-700">Profil Mahasiswa</a>.
                                </p>
                            </div>
                        </div>
                    @endif
                @endif

                <form action="{{ route('mahasiswa.ajukan_magang.store') }}" method="POST" enctype="multipart/form-data"
                    id="formAjukanMagang">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="mb-4">
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                value="{{ $mahasiswa->nama_lengkap ?? 'Wahyu Idaya' }}" readonly
                                class="w-full px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                            <input type="text" id="npm" name="npm" value="{{ $mahasiswa->npm ?? '11122465' }}" readonly
                                class="w-full px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas *</label>
                            <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $mahasiswa->kelas ?? '') }}"
                                placeholder="Masukkan Kelas"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                {{ $pengajuanAktif ? 'disabled' : 'required' }}>
                        </div>

                        <div class="mb-4">
                            <label for="ipk" class="block text-sm font-medium text-gray-700">IPK</label>
                            <input type="number" step="0.01" min="0" max="4" id="ipk" name="ipk"
                                value="{{ old('ipk', $mahasiswa->ipk ?? '') }}" placeholder="Masukkan IPK"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                {{ $pengajuanAktif ? 'disabled' : 'required' }}>
                        </div>

                        <div class="mb-4">
                            <label for="nomor_hp" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                            <input type="tel" id="nomor_hp" name="nomor_hp"
                                value="{{ old('nomor_hp', $mahasiswa->no_hp ?? '') }}" placeholder="Masukkan Nomor HP"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                {{ $pengajuanAktif ? 'disabled' : 'required' }}>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $mahasiswa->user->email ?? '') }}" placeholder="Masukkan Email Aktif"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                {{ $pengajuanAktif ? 'disabled' : 'required' }}>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select name="semester" {{ $pengajuanAktif ? 'disabled' : 'required' }}
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ $pengajuanAktif ? 'bg-gray-100' : '' }}">
                            <option value="" disabled {{ !$pengajuanAktif && !old('semester') ? 'selected' : '' }}>-- Pilih
                                Semester --</option>
                            @foreach([5, 6, 7] as $smt)
                                <option value="{{ $smt }}" {{ old('semester', $pengajuanAktif->semester ?? '') == $smt ? 'selected' : '' }}>{{ $smt }}</option>
                            @endforeach
                        </select>
                        @if($pengajuanAktif)
                            <input type="hidden" name="semester" value="{{ $pengajuanAktif->semester }}">
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan"
                            value="{{ $pengajuanAktif ? ($pengajuanAktif->mitra->nama_perusahaan ?? $pengajuanAktif->nama_perusahaan) : old('nama_perusahaan') }}"
                            placeholder="Masukkan nama lengkap perusahaan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ $pengajuanAktif ? 'bg-gray-100' : '' }}"
                            {{ $pengajuanAktif ? 'readonly' : 'required' }}>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Posisi di Perusahaan</label>
                        <input type="text" name="posisi"
                            value="{{ $pengajuanAktif ? $pengajuanAktif->posisi_dilamar : old('posisi') }}"
                            placeholder="Masukkan posisi anda di perusahaaan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ $pengajuanAktif ? 'bg-gray-100' : '' }}"
                            {{ $pengajuanAktif ? 'readonly' : 'required' }}>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
                        <textarea name="alamat_perusahaan" rows="3" placeholder="Masukkan alamat lengkap perusahaan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg {{ $pengajuanAktif ? 'bg-gray-100' : '' }}"
                            {{ $pengajuanAktif ? 'readonly' : 'required' }}>{{ $pengajuanAktif ? $pengajuanAktif->alamat_perusahaan : old('alamat_perusahaan') }}</textarea>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Upload Dokumen</h3>

                    <div class="mb-4">
                        <label for="upload_krs" class="block text-sm font-medium text-gray-700 mb-1">KRS</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="upload_krs_file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 {{ $pengajuanAktif ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">Klik untuk upload atau drag file</p>
                                    <p class="text-xs text-gray-400">PDF atau DOCX, maksimal 5MB</p>
                                </div>
                                <input id="upload_krs_file" type="file" name="krs_file" class="hidden" accept=".pdf,.doc,.docx"
                                    {{ $pengajuanAktif ? 'disabled' : 'required' }} />
                            </label>
                        </div>
                        <div id="alert_krs"
                            class="mt-2 p-2 text-sm text-green-800 bg-green-50 rounded-lg flex items-center space-x-2"
                            style="display: none;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="krs_file_name"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="upload_transkrip_nilai" class="block text-sm font-medium text-gray-700 mb-1">Transkrip
                            Nilai</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="upload_transkrip_nilai_file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 {{ $pengajuanAktif ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">Klik untuk upload atau drag file</p>
                                    <p class="text-xs text-gray-400">PDF atau DOCX, maksimal 5MB</p>
                                </div>
                                <input id="upload_transkrip_nilai_file" type="file" name="transkrip_nilai_file" class="hidden"
                                    accept=".pdf,.doc,.docx" {{ $pengajuanAktif ? 'disabled' : 'required' }} />
                            </label>
                        </div>
                        <div id="alert_transkrip"
                            class="mt-2 p-2 text-sm text-green-800 bg-green-50 rounded-lg flex items-center space-x-2"
                            style="display: none;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="transkrip_file_name"></span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surat Pengantar</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="upload_surat_pengantar_file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 {{ $pengajuanAktif ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload atau
                                            drag file</span></p>
                                </div>
                                <input id="upload_surat_pengantar_file" type="file" name="surat_pengantar_file" class="hidden"
                                    {{ $pengajuanAktif ? 'disabled' : 'required' }} />
                            </label>
                        </div>
                        <div id="alert_surat"
                            class="mt-2 p-2 text-sm text-green-800 bg-green-50 rounded-lg flex items-center space-x-2"
                            style="display: none;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="surat_file_name"></span>
                        </div>

                        @if(!$pengajuanAktif)
                            <div
                                class="mt-3 flex items-start p-3 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50">
                                <svg class="flex-shrink-0.. inline w-4 h-4 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <span class="font-medium">Belum memiliki surat pengantar?</span><br>
                                    Silakan unduh template resmi melalui link berikut:
                                    <a href="https://drive.google.com/drive/folders/1kF-_1LLgolk4mHrTjNEk2t4nYALoJQ7V?usp=sharing"
                                        target="_blank"
                                        class="font-bold underline hover:text-blue-900 decoration-2 decoration-blue-400 underline-offset-2">
                                        Klik di Sini untuk Unduh Template (.docx)
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-center">
                        @if($pengajuanAktif)
                            <button type="button" disabled
                                class="w-full md:w-auto px-10 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed opacity-60">
                                <svg class="inline-block w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Sudah Ada Pengajuan Aktif
                            </button>
                        @else
                            <button type="submit"
                                class="w-full md:w-auto px-10 py-3 bg-[#690dac] text-white font-semibold rounded-lg hover:bg-[#570992] transition duration-200 shadow-md">
                                <svg class="inline-block w-5 h-5 mr-2 -ml-1 transform rotate-45" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Pengajuan
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                window.alert("{{ session('success') }}");
            @endif

            @if(session('error'))
                window.alert("{{ session('error') }}");
            @endif
        });

        // Validasi form sebelum submit
        document.getElementById('formAjukanMagang')?.addEventListener('submit', function (e) {
            const fotoUrl = '{{ $mahasiswa->foto_url ?? "" }}';
            let errors = [];

            if (!fotoUrl) {
                errors.push('Foto formal belum diunggah');
            }

            // Validasi file upload (Hanya jika tidak sedang di-lock/aktif)
            if (!document.getElementById('upload_krs_file').disabled) {
                const krsFile = document.getElementById('upload_krs_file').files[0];
                const transkripFile = document.getElementById('upload_transkrip_nilai_file').files[0];
                const suratFile = document.getElementById('upload_surat_pengantar_file').files[0];

                if (!krsFile) errors.push('File KRS wajib diunggah');
                if (!transkripFile) errors.push('File Transkrip Nilai wajib diunggah');
                if (!suratFile) errors.push('File Surat Pengantar wajib diunggah');
            }

            if (errors.length > 0) {
                e.preventDefault();

                // Cek jika errornya karena foto
                if (!fotoUrl) {
                    const pesan = 'Pengajuan magang belum dapat dikirim.\n\n' +
                        'Anda wajib mengunggah foto formal pada Profil Mahasiswa.\n\n' +
                        'Klik "OK" untuk menuju halaman Profil Mahasiswa.';
                    if (confirm(pesan)) {
                        window.location.href = "{{ route('mahasiswa.profil_mahasiswa.index') }}";
                    }
                } else {
                    alert(errors.join('\n'));
                }
            }
        });

        // Fungsi validasi file
        function validateFile(file, maxSize = 5) {
            const maxSizeBytes = maxSize * 1024 * 1024;
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

            if (file.size > maxSizeBytes) {
                alert(`Ukuran file terlalu besar! Maksimal ${maxSize}MB`);
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Hanya PDF dan DOCX yang diizinkan.');
                return false;
            }

            return true;
        }

        // Event listeners untuk preview nama file yang dipilih
        document.getElementById('upload_krs_file')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const alertDiv = document.getElementById('alert_krs');
            if (file && validateFile(file)) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                document.getElementById('krs_file_name').textContent = `File: ${file.name} (${fileSize} MB)`;
                alertDiv.style.display = 'flex';
            } else {
                e.target.value = '';
                alertDiv.style.display = 'none';
            }
        });

        document.getElementById('upload_transkrip_nilai_file')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const alertDiv = document.getElementById('alert_transkrip');
            if (file && validateFile(file)) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                document.getElementById('transkrip_file_name').textContent = `File: ${file.name} (${fileSize} MB)`;
                alertDiv.style.display = 'flex';
            } else {
                e.target.value = '';
                alertDiv.style.display = 'none';
            }
        });

        document.getElementById('upload_surat_pengantar_file')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const alertDiv = document.getElementById('alert_surat');
            if (file && validateFile(file)) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                document.getElementById('surat_file_name').textContent = `File: ${file.name} (${fileSize} MB)`;
                alertDiv.style.display = 'flex';
            } else {
                e.target.value = '';
                alertDiv.style.display = 'none';
            }
        });
    </script>
@endsection