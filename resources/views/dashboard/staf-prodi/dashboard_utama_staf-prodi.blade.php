@extends('layouts.app_staf-prodi', ['activeMenu' => 'dashboard', 'title' => 'Dashboard Staf Prodi'])

@section('content')
    <div class="max-w-6xl mx-auto">
        
        {{-- 1. Alert Password Default --}}
        @if($isDefaultPassword)
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0..">
                        <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-semibold text-amber-800">Keamanan Akun Staf</h3>
                        <p class="text-sm text-amber-700 mt-1">
                            Anda masih menggunakan password default. Demi keamanan data magang mahasiswa, silakan segera ganti password Anda.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('staf-prodi.profil_staf') }}" 
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

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Staf Prodi</h1>

        {{-- 2. CARD STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

            {{-- Pengajuan Aktif --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-blue-200">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-2">Pengajuan Aktif</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $pengajuanAktif }}</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Pengajuan Disetujui --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-green-200">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-2">Pengajuan Disetujui</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $pengajuanDisetujui }}</p>
                </div>
                <div class="bg-green-100 rounded-lg p-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
            </div>

            {{-- Pengajuan Ditolak --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-red-200">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-2">Pengajuan Ditolak</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $pengajuanDitolak }}</p>
                </div>
                <div class="bg-red-100 rounded-lg p-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Total Mahasiswa --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-purple-200">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-2">Total Mahasiswa Magang</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- 3. AKTIVITAS TERBARU --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="divide-y divide-gray-100">
                @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="py-4 flex items-start space-x-3">
                        <span class="block w-2 h-2 mt-2 rounded-full bg-blue-500"></span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $aktivitas['nama'] }}
                                <span class="text-xs text-gray-500">({{ $aktivitas['npm'] }})</span>
                            </p>
                            <p class="text-sm text-gray-600">{{ $aktivitas['catatan'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $aktivitas['waktu'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500 text-sm">
                        Belum ada aktivitas terbaru
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection