@extends('layouts.app_mahasiswa', [
    'activeMenu' => 'konversi_sks_saya',
    'title' => 'Konversi SKS Saya'
])

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-8">Konversi SKS Magang</h1>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @forelse($pengajuanList as $item)
        @php
            $konversi = $item->konversiSks->first();
        @endphp

        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start">

                {{-- KIRI --}}
                <div class="space-y-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        {{ $item->mitra->nama_perusahaan ?? 'Nama Perusahaan' }}
                    </h2>

                    <div class="text-sm text-gray-600 space-y-1">
                        <p>Nama:
                            <span class="text-gray-800">
                                {{ $mahasiswa->nama_lengkap ?? '-' }}
                            </span>
                        </p>
                        <p>Program:
                            <span class="text-gray-800">
                                {{ $mahasiswa->prodi ?? '-' }}
                            </span>
                        </p>
                        <p>Periode:
                            <span class="text-gray-800">
                                {{ $item->periode_magang ?? '-' }}
                            </span>
                        </p>
                    </div>

                    {{-- STATUS --}}
                    <div class="mt-4">
                        @if($konversi)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-600 text-[10px] font-bold rounded-md uppercase">
                                Disetujui
                            </span>
                            <p class="text-[10px] text-gray-400 mt-2">
                                Tanggal upload:
                                {{ $konversi->created_at->format('Y-m-d') }}
                            </p>
                        @else
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-600 text-[10px] font-bold rounded-md uppercase">
                                Menunggu Proses
                            </span>
                            <p class="text-[10px] text-gray-400 mt-2">
                                Tanggal pengajuan:
                                {{ optional($item->tanggal_pengajuan)->format('Y-m-d') }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- KANAN (BUTTON UNDUH) --}}
                <div class="mt-4 md:mt-0">
                    @if($konversi)
                        <a href="{{ route('mahasiswa.konversi_sks.download', $item->id_pengajuan) }}"
                           class="flex items-center gap-2 px-6 py-2 border-2 border-[#690dac]
                                  text-[#690dac] text-sm font-semibold rounded-xl
                                  hover:bg-purple-50 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh Konversi SKS</span>
                        </a>
                    @else
                        <button disabled
                            class="flex items-center gap-2 px-6 py-2 border-2 border-gray-200
                                   text-gray-400 text-sm font-semibold rounded-xl
                                   cursor-not-allowed bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh</span>
                        </button>
                        <p class="text-[10px] text-gray-400 mt-1 text-right italic">
                            * File belum tersedia
                        </p>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
            <p class="text-gray-400 font-medium">Belum ada riwayat pengajuan magang.</p>
        </div>
    @endforelse
</div>
@endsection
