@extends('layouts.app_staf-prodi', ['activeMenu' => 'data_akun', 'title' => 'Data Akun'])

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Data Akun</h1>
    </div>
    <form method="GET" action="{{ route('staf-prodi.data_akun') }}">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 w-full">
            {{-- Input Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama atau email..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-sm">
            </div>

            {{-- Select Role (Pengganti Select Periode) --}}
        <div class="relative w-40 sm:w-48">
            <select name="role" onchange="this.form.submit()"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-gray-400 text-gray-700 bg-white appearance-none cursor-pointer text-sm">
                <option value="">Semua Role</option>
                
                {{-- Pastikan value sesuai dengan isi kolom 'role' di tabel users --}}
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                
                <option value="dosen_pembimbing" {{ request('role') == 'dosen_pembimbing' ? 'selected' : '' }}>Dosen Pembimbing</option>
                
                <option value="mitra_perusahaan" {{ request('role') == 'mitra_perusahaan' ? 'selected' : '' }}>Mitra Perusahaan</option>
            </select>
            
            {{-- Icon Panah Bawah --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

            {{-- Tombol Cari Ikon --}}
            <button type="submit"
                class="p-2.5 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </form>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#690dac] text-white">
                        <th class="px-6 py-4 font-semibold text-sm">Nama</th>
                        <th class="px-6 py-4 font-semibold text-sm">Role</th>
                        <th class="px-6 py-4 font-semibold text-sm">Email</th>
                        <th class="px-6 py-4 font-semibold text-sm">No. Telpon</th>
                        <th class="px-6 py-4 font-semibold text-sm text-center">Aksi</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                @php
                    $nama = '-';
                    $email = '-';
                    $telp = '-';

                    // LABEL
                    $roleLabel = match ($user->role) {
                        'mahasiswa' => 'Mahasiswa',
                        'dosen_pembimbing' => 'Dosen Pembimbing',
                        'mitra_perusahaan' => 'Mitra Perusahaan',
                        default => ucfirst(str_replace('_', ' ', $user->role)),
                    };

                    // MAHASISWA
                    if ($user->role === 'mahasiswa' && $user->mahasiswa) {
                        $nama  = $user->mahasiswa->nama_lengkap;
                        $email = $user->mahasiswa->email ?? '-';
                        $telp  = $user->mahasiswa->no_hp ?? '-';
                    }

                    // DOSEN PEMBIMBING
                    if ($user->role === 'dosen_pembimbing' && $user->dosenPembimbing) {
                        $nama  = $user->dosenPembimbing->nama_dosen;
                        $email = $user->dosenPembimbing->email ?? '-';
                    }

                    // MITRA PERUSAHAAN
                    if ($user->role === 'mitra_perusahaan' && $user->mitra) {
                        $nama  = $user->mitra->nama_perusahaan;
                        $email = $user->mitra->email_pic ?? '-';
                        $telp  = $user->mitra->no_telp_pic ?? '-';
                    }
                @endphp

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-gray-800">
                                {{ $nama }}
                            </td>

                            <td class="px-6 py-5">
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $email }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $telp }}
                            </td>

                            <td class="px-6 py-5 text-center">
<form action="{{ route('staf-prodi.data-akun.destroy', $user->id_user) }}"
      method="POST"
      onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
    @csrf
    @method('DELETE')

    <button type="submit" class="text-red-500 hover:text-red-700 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
            </path>
        </svg>
    </button>
</form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        {{-- PAGINATION (Identik dengan halaman sebelumnya) --}}
        <div class="bg-white px-4 py-4 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between items-center sm:hidden">
                {{-- Tampilan Mobile --}}
                @if ($users->onFirstPage())
                    <span class="text-sm text-gray-400">Sebelumnya</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="text-sm text-gray-600">Sebelumnya</a>
                @endif

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="text-sm text-gray-600">Berikutnya</a>
                @else
                    <span class="text-sm text-gray-400">Berikutnya</span>
                @endif
            </div>

            {{-- Tampilan Desktop --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari total {{ $users->total() }} data
                    </p>
                </div>

                <div>
                    <nav class="relative z-0 inline-flex items-center space-x-6" aria-label="Pagination">
                        {{-- Tombol Sebelumnya --}}
                        @if ($users->onFirstPage())
                            <span class="text-sm text-gray-400 cursor-default">Sebelumnya</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Sebelumnya</a>
                        @endif

                        {{-- Kotak Angka Halaman --}}
                        <div class="border border-gray-300 rounded-md px-4 py-1.5 text-sm text-gray-600 bg-white shadow-sm font-medium">
                            {{ $users->currentPage() }}
                        </div>

                        {{-- Tombol Berikutnya --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Berikutnya</a>
                        @else
                            <span class="text-sm text-gray-400 cursor-default">Berikutnya</span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection