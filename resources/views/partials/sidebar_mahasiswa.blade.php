<aside id="sidebar-mahasiswa"
    class="fixed top-0 left-0 w-64 h-full bg-[#690dac] text-white flex flex-col pt-4 pb-2 overflow-y-auto shadow-lg z-10">
    <div class="px-6 pb-3">
        <div class="flex flex-col">
            <div
                class="bg-white text-[#690dac] w-12 h-10 flex items-center justify-center rounded-lg text-xl font-bold mb-2">
                UG</div>
            <div class="text-lg font-semibold -mb-1">Dashboard Mahasiswa</div>
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
            <a href="{{ route('dashboard.mahasiswa') }}" class="
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
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </a>

            {{-- Ajukan Magang --}}
            <a href="{{ route('mahasiswa.ajukan_magang') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'ajukan_magang') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                    </path>
                </svg>
                Ajukan Magang
            </a>

            {{-- Status Pengajuan --}}
            <a href="{{ route('mahasiswa.status_pengajuan') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'status_pengajuan') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                Status Pengajuan
            </a>


            {{-- Silabus dari Mitra --}}
            <a href="{{ route('mahasiswa.silabus_dari_mitra.index') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'silabus_dari_mitra') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                Silabus dari Mitra
            </a>

            {{-- Cetak Surat Pengantar --}}
            <a href="{{ route('mahasiswa.cetak_surat_pengantar') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'cetak_surat_pengantar') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak Surat Pengantar
            </a>

            {{-- Konversi SKS Saya --}}
            <a href="{{ route('mahasiswa.konversi_sks_saya') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'konversi_sks_saya') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Konversi SKS Saya
            </a>

            {{-- Laporan --}}
            <a href="{{ route('mahasiswa.laporan') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'laporan') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Laporan
            </a>

            {{-- Riwayat Magang --}}
            <a href="{{ route('mahasiswa.riwayat_magang') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'riwayat_magang') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Riwayat Magang
            </a>
        </div>
    </nav>
    <hr class="border-t border-[#883dbd] mx-6 mt-auto mb-2">
    <div class="px-4 pb-2">
        {{-- Profil Saya --}}
        <a href="{{ route('mahasiswa.profil_mahasiswa.index') }}" class="
            {{ $baseCompactClass }} 
            @if(isset($activeMenu) && $activeMenu === 'profil_mahasiswa') 
                {{ $activeClass }} 
            @else 
                {{ $inactiveClass }} 
            @endif
        ">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Saya
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