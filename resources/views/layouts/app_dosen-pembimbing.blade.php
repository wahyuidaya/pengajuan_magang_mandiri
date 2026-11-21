<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Dosen Pembimbing' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* Mobile Responsive Table Horizontal Scroll */
.table-scroll-wrap {
  width: 100%;
  max-width: 100%;
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch;
  display: block;
}
@media (max-width: 900px) {
  html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
  }
  main, .main-content, .content, .content-wrapper, .page-content, .card, .panel {
    max-width: 100% !important;
    min-width: 0 !important;
  }
  .table-responsive, .table-wrap, .table-container {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
    width: 100% !important;
    max-width: 100% !important;
    display: block;
  }
  .table-scroll-wrap table {
    width: 100%;
    min-width: max-content;
    white-space: nowrap;
  }
  .sidebar, aside, nav.sidebar {
    height: 100dvh !important;
    max-height: 100dvh !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
    padding-bottom: max(24px, env(safe-area-inset-bottom)) !important;
  }
}
</style>
</head>

<body class="font-sans flex flex-col md:flex-row bg-gray-50 min-h-screen">

    {{-- Mobile Topbar (Coffeebox style: hamburger di dalam bar rapih) --}}
    <header class="mobile-topbar">
        <div class="topbar-left">
            <button type="button" class="mobile-menu-toggle" onclick="toggleSidebar('sidebar-dosen')" aria-label="Buka Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="flex items-center gap-2">
                <span class="bg-white text-[#690dac] text-xs font-black px-1.5 py-0.5 rounded shadow-sm">UG</span>
                <span class="text-white text-sm font-semibold tracking-wide">Dashboard Dosen</span>
            </div>
        </div>
        <div class="text-xs text-purple-200 opacity-80 hidden sm:block">
            Gunadarma
        </div>
    </header>

    {{-- Overlay gelap saat sidebar drawer terbuka di mobile --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar('sidebar-dosen')"></div>

    @include('partials.sidebar_dosen-pembimbing', ['activeMenu' => $activeMenu ?? 'dashboard'])

    <main class="ml-64 px-10 py-6 w-full dashboard-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ---- Hamburger Sidebar Toggle (Drawer Coffeebox Style) ---- */
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

        /* Saat klik menu di sidebar mobile: drawer meluncur tutup dulu, lalu navigasi jalan mulus */
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('aside[id^="sidebar-"] a');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        const targetHref = link.getAttribute('href');
                        if (!targetHref || targetHref === '#' || targetHref.startsWith('javascript:')) {
                            return;
                        }

                        e.preventDefault();
                        const aside = link.closest('aside');
                        if (aside) closeSidebar(aside.id);

                        setTimeout(function() {
                            window.location.href = targetHref;
                        }, 200);
                    }
                });
            });
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('table').forEach(function(t) {
    if (!t.parentElement || t.parentElement.classList.contains('table-scroll-wrap')) return;
    var w = document.createElement('div');
    w.className = 'table-scroll-wrap';
    t.parentNode.insertBefore(w, t);
    w.appendChild(t);
  });
});
</script>
</body>

</html>
