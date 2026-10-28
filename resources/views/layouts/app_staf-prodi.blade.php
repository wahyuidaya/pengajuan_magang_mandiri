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
    @include('partials.sidebar_staf-prodi', ['activeMenu' => $activeMenu ?? 'dashboard'])
    <main class="ml-64 px-10 py-6 w-full">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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

        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('sidebar-prodi');

            // 1. Ambil posisi scroll yang tersimpan di localStorage
            const scrollPosition = localStorage.getItem('sidebar-scroll');
            if (scrollPosition) {
                sidebar.scrollTop = scrollPosition;
            }

            // 2. Simpan posisi scroll saat pengguna mengklik menu apa pun di dalam sidebar
            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
                });
            });

            // Simpan juga saat logout diklik (jika perlu)
            const logoutBtn = sidebar.querySelector('button[onclick="handleLogout()"]');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    localStorage.setItem('sidebar-scroll', sidebar.scrollTop);
                });
            }
        });
    </script>
</body>

</html>