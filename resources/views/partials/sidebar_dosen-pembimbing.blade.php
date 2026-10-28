<aside id="sidebar-dosen"
    class="fixed top-0 left-0 w-64 h-full bg-[#690dac] text-white flex flex-col pt-4 pb-2 overflow-y-auto shadow-lg z-10">
    <div class="px-6 pb-3">
        <div class="flex flex-col">
            <div
                class="bg-white text-[#690dac] w-12 h-10 flex items-center justify-center rounded-lg text-xl font-bold mb-2">
                UG</div>
            <div class="text-lg font-semibold -mb-1">Dashboard Dosen Pembimbing</div>
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
            <a href="{{ route('dashboard.dosen-pembimbing') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'dashboard') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>

            {{-- Daftar Mahasiswa Bimbingan --}}
            <a href="{{ route('dosen-pembimbing.daftar_mahasiswa_bimbingan') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'daftar_mahasiswa_bimbingan') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Daftar Mahasiswa Bimbingan
            </a>

            {{-- Laporan Mahasiswa --}}
            <a href="{{ route('dosen-pembimbing.laporan_mahasiswa') }}" class="
                {{ $baseCompactClass }} 
                @if(isset($activeMenu) && $activeMenu === 'laporan_mahasiswa') 
                    {{ $activeClass }} 
                @else 
                    {{ $inactiveClass }} 
                @endif
            ">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Laporan Mahasiswa
            </a>
        </div>
    </nav>
    <hr class="border-t border-[#883dbd] mx-6 mt-auto mb-2">
    <div class="px-4 pb-2">
        {{-- Profil --}}
        <a href="{{ route('dosen-pembimbing.profil_dosen.index') }}" class="
            {{ $baseCompactClass }} 
            @if(isset($activeMenu) && $activeMenu === 'profil_dosen') 
                {{ $activeClass }} 
            @else 
                {{ $inactiveClass }} 
            @endif
        ">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $strokeWidth }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil
        </a>
        
        {{-- Logout --}}
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