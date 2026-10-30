<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Staf Prodi' }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-sans flex bg-gray-50">

    {{-- Hamburger Button (mobile only) --}}
    <button class="hamburger-btn" id="hamburger-btn" onclick="toggleSidebar('sidebar-prodi')" aria-label="Buka Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    {{-- Overlay gelap saat sidebar terbuka di mobile --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar('sidebar-prodi')"></div>

    @include('partials.sidebar_staf-prodi', ['activeMenu' => $activeMenu ?? 'dashboard'])

    <main class="ml-64 px-10 py-6 w-full dashboard-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ---- Hamburger Sidebar Toggle ---- */
        function toggleSidebar(sidebarId) {
            const sidebar = document.getElementById(sidebarId);
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('sidebar-open');
            overlay.classList.toggle('show');
        }

        function closeSidebar(sidebarId) {
            const sidebar = document.getElementById(sidebarId);
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('sidebar-open');
            overlay.classList.remove('show');
        }

        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('sidebar-prodi');

            const scrollPosition = localStorage.getItem('sidebar-scroll');
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }

            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
                    if (window.innerWidth < 768) {
                        closeSidebar('sidebar-prodi');
                    }
                });
            });

            const logoutBtn = sidebar.querySelector('button[onclick="handleLogout()"]');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
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
