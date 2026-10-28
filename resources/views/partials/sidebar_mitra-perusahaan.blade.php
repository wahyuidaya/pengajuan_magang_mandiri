<aside id="sidebar-mitra"
    class="fixed top-0 left-0 w-64 h-full bg-[#690dac] text-white flex flex-col pt-4 pb-2 overflow-y-auto shadow-lg z-10">
    <div class="px-6 pb-3">
        <div class="flex flex-col">
            <div
                class="bg-white text-[#690dac] w-12 h-10 flex items-center justify-center rounded-lg text-xl font-bold mb-2">
                UG</div>
            <div class="text-lg font-semibold -mb-1">Dashboard Mitra Perusahaan</div>
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
        <div class="space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard.mitra-perusahaan') }}" class="
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
                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                    </path>
                </svg>
                Dashboard
            </a>

            {{-- Data Mahasiswa --}}
            <a href="{{ route('mitra-perusahaan.mitra_perusahaan_data_mahasiswa') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'mitra_perusahaan_data_mahasiswa') 
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

            {{-- Penilaian --}}
            <a href="{{ route('mitra-perusahaan.penilaian') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'penilaian') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-1.006 3.42 3.42 0 014.438 0c.699.64 1.257 1.084 1.946 1.006a3.42 3.42 0 013.824 3.824c-.078.69.366 1.247 1.006 1.946a3.42 3.42 0 010 4.438c-.64.699-1.084 1.257-1.006 1.946a3.42 3.42 0 01-3.824 3.824c-.69-.078-1.247.366-1.946 1.006a3.42 3.42 0 01-4.438 0c-.699-.64-1.257-1.084-1.946-1.006a3.42 3.42 0 01-3.824-3.824c.078-.69-.366-1.247-1.006-1.946a3.42 3.42 0 010-4.438c.64-.699 1.084-1.257 1.006-1.946a3.42 3.42 0 013.824-3.824z">
                    </path>
                </svg>
                Penilaian
            </a>

            {{-- Kirim Silabus --}}
            <a href="{{ route('mitra-perusahaan.kirim_silabus.index') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'kirim_silabus') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5 transform rotate-45 -translate-x-0.. -translate-y-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Kirim Silabus
            </a>
        </div>
    </nav>
    <hr class="border-t border-[#883dbd] mx-6 mt-auto mb-2">
    <div class="px-4 pb-2">
        {{-- Profil --}}
        <a href="{{ route('mitra-perusahaan.profil_mitra.index') }}" class="
            {{ $baseCompactClass }} 
            @if(isset($activeMenu) && $activeMenu === 'profil_mitra') 
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