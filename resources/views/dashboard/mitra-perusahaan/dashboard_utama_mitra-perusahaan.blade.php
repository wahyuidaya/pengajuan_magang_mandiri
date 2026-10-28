@extends('layouts.app_mitra-perusahaan', ['activeMenu' => 'dashboard', 'title' => 'Dashboard Mitra Perusahaan'])
@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Alert Password Default --}}
        @if($isDefaultPassword)
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0..">
                        <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-semibold text-amber-800">
                            Keamanan Akun Anda
                        </h3>
                        <p class="text-sm text-amber-700 mt-1">
                            Anda masih menggunakan password default. Untuk keamanan akun, silakan segera ganti password Anda.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('mitra-perusahaan.profil_mitra.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Ganti Password Sekarang
                            </a>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.bg-amber-50').remove()" class="ml-3 flex-shrink-0.. text-amber-400 hover:text-amber-600 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Mitra Perusahaan</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Card Total Mahasiswa - CLICKABLE --}}
            <a href="{{ route('mitra-perusahaan.mitra_perusahaan_data_mahasiswa') }}" class="bg-white p-6 rounded-xl border border-gray-200
       flex items-center justify-between cursor-pointer
       transition-all duration-200 ease-out
       hover:border-gray-300
       hover:-translate-y-px">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Mahasiswa Magang</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-violet-100 rounded-xl p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#883dbd]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                    </div>
                </div>
            </a>

            {{-- Card Mahasiswa Aktif - CLICKABLE --}}
            <a href="{{ route('mitra-perusahaan.mitra_perusahaan_data_mahasiswa') }}" class="bg-white p-6 rounded-xl border border-gray-200
       flex items-center justify-between cursor-pointer
       transition-all duration-200 ease-out
       hover:border-gray-300
       hover:-translate-y-px">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Mahasiswa Aktif</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $mahasiswaAktif }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 rounded-xl p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="divide-y divide-gray-100">
                @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="py-4 flex items-start space-x-3">
                        <span class="block w-2 h-2 mt-2 rounded-full bg-[#883dbd]"></span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $aktivitas['nama'] }}
                                <span class="text-xs font-normal text-gray-500">({{ $aktivitas['npm'] }})</span>
                            </p>
                            <p class="text-sm text-gray-600">{{ $aktivitas['catatan'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $aktivitas['periode'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada aktivitas penilaian</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection