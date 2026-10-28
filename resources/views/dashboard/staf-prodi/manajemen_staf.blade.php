@extends('layouts.app_staf-prodi', ['activeMenu' => 'manajemen_staf', 'title' => 'Manajemen Staf'])

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Staf</h1>
        <p class="mt-2 text-sm text-gray-600">Kelola akun dan hak akses personil staf program studi</p>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- FORM TAMBAH (KIRI) --}}
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-[#690dac] px-5 py-4 text-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Tambah Akun</h3>
                </div>
                
                <form action="{{ route('staf-prodi.manajemen_staf.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Username</label>
                        <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-[#690dac] outline-none text-sm transition-all" placeholder="Masukkan username" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-[#690dac] outline-none text-sm transition-all" placeholder="Masukkan Email" required>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-[11px] text-amber-700 italic">
                        Password default: <span class="font-bold">password123</span>
                    </div>
                    <button type="submit" class="w-full bg-[#690dac] hover:bg-purple-800 text-white font-bold py-3.5 rounded-xl shadow-md transition-all active:scale-95 text-sm">
                        Daftarkan Staf
                    </button>
                </form>
            </div>
        </div>

        {{-- TABEL DATA (KANAN) --}}
        <div class="flex-grow">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            {{-- Header table disamakan warnanya dengan halaman konversi --}}
                            <tr class="bg-[#690dac] text-white">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Informasi Akun</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staf_list as $staf)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 text-sm">{{ $staf->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $staf->email }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $staf->is_password_changed ? 'bg-green-50 text-green-600 border-green-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                        {{ $staf->is_password_changed ? 'Aktif' : 'Default' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('staf-prodi.manajemen_staf.reset', $staf->id_user) }}" method="POST" onsubmit="return confirm('Reset password?')">
                                            @csrf
                                            <button type="submit" class="p-2 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('staf-prodi.manajemen_staf.delete', $staf->id_user) }}" method="POST" onsubmit="return confirm('Hapus akun?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500 text-sm">Belum ada data staf</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success')) alert("{{ session('success') }}"); @endif
        @if(session('error')) alert("{{ session('error') }}"); @endif
        @if($errors->any()) alert("{{ $errors->first() }}"); @endif
    });
</script>
@endsection