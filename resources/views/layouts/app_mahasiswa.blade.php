<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Mahasiswa' }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="font-sans flex flex-col md:flex-row bg-gray-50 min-h-screen">

    {{-- Mobile Topbar (Coffeebox style: hamburger di dalam bar rapih) --}}
    <header class="mobile-topbar">
        <div class="topbar-left">
            <button type="button" class="mobile-menu-toggle" onclick="toggleSidebar('sidebar-mahasiswa')" aria-label="Buka Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="flex items-center gap-2">
                <span class="bg-white text-[#690dac] text-xs font-black px-1.5 py-0.5 rounded shadow-sm">UG</span>
                <span class="text-white text-sm font-semibold tracking-wide">Dashboard Mahasiswa</span>
            </div>
        </div>
        <div class="text-xs text-purple-200 opacity-80 hidden sm:block">
            Gunadarma
        </div>
    </header>

    {{-- Overlay gelap saat sidebar drawer terbuka di mobile --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar('sidebar-mahasiswa')"></div>

    @include('partials.sidebar_mahasiswa', ['activeMenu' => $activeMenu ?? 'dashboard'])

    <main class="ml-64 px-10 py-6 w-full dashboard-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ---- Hamburger Sidebar Toggle (Drawer) ---- */
        function toggleSidebar(sidebarId) {
            const sidebar = document.getElementById(sidebarId);
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('sidebar-open');
                overlay.classList.toggle('show');
            }
        }

        function closeSidebar(sidebarId) {
            const sidebar = document.getElementById(sidebarId);
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('show');
            }
        }

        /* Tutup sidebar drawer saat link diklik di mobile */
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar-mahasiswa');

            // Scroll restore
            const savedScroll = localStorage.getItem('mahasiswa-sidebar-scroll');
            if (savedScroll && sidebar) {
                sidebar.scrollTop = savedScroll;
            }

            if (sidebar) {
                sidebar.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('button')) {
                        localStorage.setItem('mahasiswa-sidebar-scroll', sidebar.scrollTop);
                        if (window.innerWidth <= 768) {
                            closeSidebar('sidebar-mahasiswa');
                        }
                    }
                });
            }
        });

        /* ---- Logout Confirm ---- */
        function handleLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                iconColor: '#690dac',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'px-6 py-2 bg-[#690dac] text-white rounded-lg font-semibold mx-2 hover:bg-[#5a0b94] transition-all',
                    cancelButton: 'px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold mx-2 hover:bg-gray-200 transition-all'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>
</body>

</html>
