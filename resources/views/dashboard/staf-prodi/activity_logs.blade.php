@extends('layouts.app_staf-prodi', ['activeMenu' => 'activity_logs', 'title' => 'Monitoring Activity Logs'])

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Monitoring Aktivitas Staf</h1>
    <p class="text-sm text-gray-600">Catatan riwayat aksi penambahan dan penghapusan data oleh staf prodi.</p>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white uppercase bg-[#690dac]">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Nama Staf</th>
                    <th class="px-6 py-4">Aksi</th>
                    <th class="px-6 py-4">Target Data</th>
                    <th class="px-6 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($activity_logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-semibold">
                            {{ $log->username }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $aksiLower = strtolower($log->aksi);
                            $class = 'text-blue-600';
                            if(str_contains($aksiLower, 'hapus')) $class = 'text-red-600 font-bold';
                            if(str_contains($aksiLower, 'tambah')) $class = 'text-green-600 font-bold';
                        @endphp
                        <span class="{{ $class }}">{{ $log->aksi }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 uppercase text-[10px] font-bold">
                        {{ $log->target }}
                    </td>
                    <td class="px-6 py-4">
                        <code class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-500">{{ $log->ip_address }}</code>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-gray-400">Belum ada rekaman aktivitas yang tercatat.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Area --}}
    @if($activity_logs->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $activity_logs->links() }}
    </div>
    @endif
</div>
@endsection