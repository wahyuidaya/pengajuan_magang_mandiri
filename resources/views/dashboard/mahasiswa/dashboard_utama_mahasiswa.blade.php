@extends('layouts.app_mahasiswa', ['activeMenu' => 'dashboard', 'title' => 'Dashboard Mahasiswa'])

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Mahasiswa</h1>

{{-- CARD 3 MAHASISWA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Status Pengajuan Terbaru --}}
        <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-blue-200">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">Status Pengajuan Terbaru</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $pengajuan->status ?? 'Belum Ada' }}
                </p>
            </div>
            <div class="bg-blue-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        {{-- Dosen Pembimbing --}}
        <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-green-200">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">Dosen Pembimbing</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $pengajuan->dosenPembimbing->nama_dosen ?? 'Belum Ditentukan' }}
                </p>
            </div>
            <div class="bg-green-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v3m-2-1.5h4" />
                </svg>
            </div>
        </div>

        {{-- IPK Kumulatif --}}
        <div class="bg-white p-6 rounded-xl shadow-md flex items-start justify-between border border-purple-200">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">IPK Kumulatif</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $mahasiswa->ipk ?? '-' }}
                </p>
            </div>
            <div class="bg-purple-100 rounded-lg p-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>

    </div>

    {{-- AKTIVITAS TERBARU --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

        <div class="divide-y divide-gray-100">
            @forelse($aktivitasTerbaru as $log)
                <div class="py-4 flex items-start space-x-3">
                    <span class="block w-2 h-2 mt-2 rounded-full bg-blue-500"></span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $log->status_dari }} → {{ $log->status_ke }}
                            <span class="text-xs text-gray-500">oleh {{ $log->user->name ?? 'System' }}</span>
                        </p>
                        @if($log->catatan)
                            <p class="text-sm text-gray-600">{{ $log->catatan }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $log->timestamp->format('d M Y H:i') }}</p>
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
