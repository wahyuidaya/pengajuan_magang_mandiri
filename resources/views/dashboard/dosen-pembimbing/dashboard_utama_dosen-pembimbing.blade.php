@extends('layouts.app_dosen-pembimbing', ['activeMenu' => 'dashboard', 'title' => 'Dashboard Dosen Pembimbing'])

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
                        <a href="{{ route('dosen-pembimbing.profil_dosen.index') }}" 
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

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Dosen Pembimbing</h1>

    {{-- CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Jumlah Mahasiswa Bimbingan --}}
        <div class="bg-white p-6 rounded-xl shadow-md border border-blue-200 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">
                    Jumlah Mahasiswa Bimbingan
                </p>
                <p class="text-4xl font-bold text-gray-800">
                    {{ $jumlahMahasiswa }}
                </p>
            </div>
            <div class="bg-blue-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7
                           m10 0v-2c0-.656-.126-1.305-.356-1.857
                           M7 20H2v-2a3 3 0 015.356-1.857
                           M7 20v-2c0-.656.126-1.305.356-1.857
                           m0 0a5.002 5.002 0 019.288 0
                           M15 7a3 3 0 11-6 0 3 3 0 016 0
                           zm6 3a2 2 0 11-4 0 2 2 0 014 0
                           zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        {{-- laporan mingguan --}}
        <div class="bg-white p-6 rounded-xl shadow-md border border-violet-200 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">
                    laporan mingguan
                </p>
                <p class="text-4xl font-bold text-gray-800">
                    {{ $laporanmingguan }}
                </p>
            </div>
            <div class="bg-violet-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#690dac]"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6
                           m2 5H7a2 2 0 01-2-2V5
                           a2 2 0 012-2h5.586
                           a1 1 0 01.707.293
                           l5.414 5.414
                           a1 1 0 01.293.707
                           V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        {{-- Laporan Akhir --}}
        <div class="bg-white p-6 rounded-xl shadow-md border border-green-200 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">
                    Laporan Akhir
                </p>
                <p class="text-4xl font-bold text-gray-800">
                    {{ $laporanAkhir }}
                </p>
            </div>
            <div class="bg-green-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.253v13
                           m0-13C10.832 5.477 9.246 5 7.5 5
                           S4.168 5.477 3 6.253v13
                           C4.168 18.477 5.754 18 7.5 18
                           s3.332.477 4.5 1.253
                           m0-13C13.168 5.477 14.754 5 16.5 5
                           s3.332.477 4.5 1.253v13
                           C19.832 18.477 18.246 18 16.5 18
                           s-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>

    </div>


    {{-- AKTIVITAS TERBARU --}}
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