@extends('layouts.app_mahasiswa', ['activeMenu' => 'riwayat_magang', 'title' => 'Riwayat Magang'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Magang</h1>
        
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="space-y-6">
            @forelse($riwayatMagang as $magang)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-[#690dac] p-5 text-white flex justify-between items-start rounded-t-xl">
                        <div>
                            <h2 class="text-xl font-bold mb-1">{{ $magang['nama_perusahaan'] }}</h2>
                            <p class="text-sm">Posisi: {{ $magang['posisi'] }}</p>
                            <p class="text-sm mb-3">Periode: {{ $magang['periode'] }}</p>
                            <div class="flex space-x-3 text-xs">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-violet-800 text-white font-semibold">
                                    Status: {{ $magang['status'] }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white text-violet-700 font-bold">
                                    Nilai Akhir: {{ $magang['nilai_akhir'] }}
                                </span>
                            </div>
                        </div>
                            <a href="{{ route('mahasiswa.riwayat_magang.download', $magang['id_pengajuan']) }}"
                            class="flex items-center bg-white text-violet-700 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 11.586V3a1 1 0 112 0v8.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Unduh Nilai
                            </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden p-12">
                    <div class="flex flex-col items-center text-center">
                        <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Riwayat Magang</h3>
                        <p class="text-gray-500 mb-6 max-w-md">
                            Anda belum memiliki riwayat magang yang selesai. Riwayat akan muncul setelah magang Anda selesai dan dinilai.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection