<aside id="sidebar-prodi"
    class="fixed top-0 left-0 w-64 h-full bg-[#690dac] text-white flex flex-col pt-4 pb-2 overflow-y-auto shadow-lg z-10">
    <div class="px-6 pb-3">
        <div class="flex flex-col">
            <div
                class="bg-white text-[#690dac] w-12 h-10 flex items-center justify-center rounded-lg text-xl font-bold mb-2">
                UG</div>
            <div class="text-lg font-semibold -mb-1">Dashboard Staf Prodi</div>
            <div class="text-xs text-purple-200">Universitas Gunadarma</div>
        </div>
    </div>
    <hr class="border-t border-[#883dbd] mx-6 mb-4">
    <nav class="flex flex-col px-4">
        @php
            $activeClass = 'bg-white text-purple-800 font-bold shadow-md';
            $inactiveClass = 'text-white hover:bg-[#7a1dbd] hover:text-white';
            $baseCompactClass = 'flex items-center gap-3 p-2 text-sm font-medium rounded-lg transition-colors duration-200';
            $strokeWidth = '1.5';
        @endphp
        <div class="space-y-2">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard.staf-prodi') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'dashboard') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            {{-- Verifikasi Pengajuan --}}
            <a href="{{ route('staf-prodi.verifikasi_pengajuan') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'verifikasi_pengajuan') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
                Verifikasi Pengajuan
            </a>

            {{-- Data Mahasiswa --}}
            <a href="{{ route('staf-prodi.staf_prodi_data_mahasiswa') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'staf_prodi_data_mahasiswa') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Data Mahasiswa
            </a>

            {{-- Surat Pengantar --}}
            <a href="{{ route('staf-prodi.surat_pengantar') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'surat_pengantar') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                Surat Pengantar
            </a>

            {{-- Konversi SKS Mahasiswa --}}
            <a href="{{ route('staf-prodi.konversi_sks') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'konversi_sks') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                    </path>
                </svg>
                Konversi SKS Mahasiswa
            </a>

            {{-- Data Dosen Pembimbing --}}
            <a href="{{ route('staf-prodi.data_dosen_pembimbing') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'data_dosen_pembimbing') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                Data Dosen Pembimbing
            </a>

            {{-- Data Mitra dan Penilaian --}}
            <a href="{{ route('staf-prodi.data_mitra_dan_penilaian') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'data_mitra_dan_penilaian') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                Data Mitra dan Penilaian
            </a>

            {{-- Data Akun --}}
            <a href="{{ route('staf-prodi.data_akun') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'data_akun') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Data Akun
            </a>
            {{-- Manajemen Akun Staf (TAMBAHKAN INI) --}}
            <a href="{{ route('staf-prodi.manajemen_staf') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'manajemen_staf') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Manajemen Staf
            </a>
            {{-- Activity Logs --}}
            <a href="{{ route('staf-prodi.activity_logs') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'activity_logs') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Activity Logs
            </a>

            {{-- Arsip Data Magang --}}
            <a href="{{ route('staf-prodi.arsip_data_magang') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'arsip_data_magang') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Arsip Data Magang
            </a>

            {{-- FAQ --}}
            <a href="{{ route('staf-prodi.faq') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'faq') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                FAQ
            </a>
        </div>
    </nav>
    <hr class="border-t border-[#883dbd] mx-6 mt-auto mb-2">
    <div class="px-4 pb-2">
        {{-- Profil --}}
        <a href="{{ route('staf-prodi.profil_staf') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'profil_staf') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                </path>
            </svg>
            Profil
        </a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" onclick="handleLogout()"
            class="{{ $baseCompactClass }} {{ $inactiveClass }} w-full justify-start mt-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Logout
        </button>
    </div>
</aside>