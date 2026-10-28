<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Magang Mandiri - Universitas Gunadarma</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAF7FC;
            color: #1F142B;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <!-- Top Simple Nav -->
    <header style="background-color: #240638; border-bottom: 1px solid rgba(186, 104, 200, 0.25);"
            class="w-full px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div style="background-color: rgba(186, 104, 200, 0.15); color: #E1BEE7; border: 1px solid rgba(186, 104, 200, 0.3);"
                 class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-sm">
                UG
            </div>
            <div>
                <span style="color: #FEFCF7;" class="font-bold text-base tracking-wide block leading-none">
                    Pengajuan Magang Mandiri
                </span>
                <span style="color: #D1C4E9;" class="text-[11px] block mt-1">
                    Universitas Gunadarma
                </span>
            </div>
        </div>

        <a href="https://wahyuidaya.github.io"
           target="_blank"
           rel="noopener noreferrer"
           style="color: #E1BEE7; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(186, 104, 200, 0.25);"
           class="text-xs px-3.5 py-1.5 rounded-full hover:bg-white/10 transition-colors inline-flex items-center gap-1.5">
            Portofolio Wahyu Idaya 
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>
    </header>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-5 py-12 md:py-16 w-full flex-1 flex flex-col justify-center">
        <!-- Title / Intro -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span style="color: #690dac; background-color: rgba(105, 13, 172, 0.08); border-color: #E8DEF8;"
                  class="inline-block text-xs font-semibold px-3 py-1 rounded-full border mb-3 tracking-wide">
                Program Studi Sistem Informasi
            </span>
            <h1 style="color: #1F142B;" class="text-2xl sm:text-3xl font-bold tracking-tight mb-3">
                Selamat Datang di Sistem Magang Mandiri
            </h1>
            <p style="color: #6E5D7E;" class="text-sm sm:text-base leading-relaxed">
                Pilih portal di bawah ini untuk mencoba alur pengajuan mandiri mahasiswa atau manajemen operasional prodi:
            </p>
        </div>

        <!-- 4 Role Cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
            <!-- 1. Mahasiswa -->
            <div style="background-color: #FFFFFF; border-color: #EDE4F5;"
                 class="rounded-xl border p-5 flex flex-col justify-between hover:border-[#690dac] hover:shadow-md transition-all">
                <div>
                    <div style="background-color: #F4EDFA; color: #690dac; border-color: #EDE4F5;"
                         class="w-10 h-10 rounded-lg border flex items-center justify-center mb-4 text-lg">
                        🎓
                    </div>
                    <h2 style="color: #1F142B;" class="text-base font-bold mb-1.5">
                        Portal Mahasiswa
                    </h2>
                    <p style="color: #6E5D7E;" class="text-xs leading-relaxed mb-4">
                        Pendaftaran magang online, upload berkas KRS (ekstraksi teks via PDFParser), dan pantau bimbingan.
                    </p>
                </div>

                <div>
                    <div style="background-color: #FAF7FC; color: #4A305A; border-color: #EDE4F5;"
                         class="rounded-lg p-2.5 mb-3.5 text-[11px] border">
                        Akun: <strong>mahasiswa@gmail.com</strong><br>
                        Sandi: <strong>mahasiswa123</strong>
                    </div>
                    <a href="{{ route('login') }}?role=mahasiswa"
                       style="background-color: #690dac; color: #FFFFFF;"
                       class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity">
                        Masuk Mahasiswa →
                    </a>
                </div>
            </div>

            <!-- 2. Staf Prodi -->
            <div style="background-color: #FFFFFF; border-color: #EDE4F5;"
                 class="rounded-xl border p-5 flex flex-col justify-between hover:border-[#690dac] hover:shadow-md transition-all">
                <div>
                    <div style="background-color: #F4EDFA; color: #690dac; border-color: #EDE4F5;"
                         class="w-10 h-10 rounded-lg border flex items-center justify-center mb-4 text-lg">
                        🏛️
                    </div>
                    <h2 style="color: #1F142B;" class="text-base font-bold mb-1.5">
                        Portal Staf Prodi
                    </h2>
                    <p style="color: #6E5D7E;" class="text-xs leading-relaxed mb-4">
                        Verifikasi berkas persyaratan magang, ploting dosen pembimbing, dan cetak surat pengantar DomPDF.
                    </p>
                </div>

                <div>
                    <div style="background-color: #FAF7FC; color: #4A305A; border-color: #EDE4F5;"
                         class="rounded-lg p-2.5 mb-3.5 text-[11px] border">
                        Akun: <strong>stafprodi@gmail.com</strong><br>
                        Sandi: <strong>stafprodi123</strong>
                    </div>
                    <a href="{{ route('login') }}?role=staf_prodi"
                       style="background-color: #690dac; color: #FFFFFF;"
                       class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity">
                        Masuk Staf Prodi →
                    </a>
                </div>
            </div>

            <!-- 3. Dosen Pembimbing -->
            <div style="background-color: #FFFFFF; border-color: #EDE4F5;"
                 class="rounded-xl border p-5 flex flex-col justify-between hover:border-[#690dac] hover:shadow-md transition-all">
                <div>
                    <div style="background-color: #F4EDFA; color: #690dac; border-color: #EDE4F5;"
                         class="w-10 h-10 rounded-lg border flex items-center justify-center mb-4 text-lg">
                        👨‍🏫
                    </div>
                    <h2 style="color: #1F142B;" class="text-base font-bold mb-1.5">
                        Portal Dosen
                    </h2>
                    <p style="color: #6E5D7E;" class="text-xs leading-relaxed mb-4">
                        Daftar mahasiswa bimbingan, review laporan logbook mingguan, serta evaluasi catatan akhir.
                    </p>
                </div>

                <div>
                    <div style="background-color: #FAF7FC; color: #4A305A; border-color: #EDE4F5;"
                         class="rounded-lg p-2.5 mb-3.5 text-[11px] border">
                        Akun: <strong>dosen@gmail.com</strong><br>
                        Sandi: <strong>dosen123</strong>
                    </div>
                    <a href="{{ route('login') }}?role=dosen_pembimbing"
                       style="background-color: #690dac; color: #FFFFFF;"
                       class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity">
                        Masuk Dosen →
                    </a>
                </div>
            </div>

            <!-- 4. Mitra Perusahaan -->
            <div style="background-color: #FFFFFF; border-color: #EDE4F5;"
                 class="rounded-xl border p-5 flex flex-col justify-between hover:border-[#690dac] hover:shadow-md transition-all">
                <div>
                    <div style="background-color: #F4EDFA; color: #690dac; border-color: #EDE4F5;"
                         class="w-10 h-10 rounded-lg border flex items-center justify-center mb-4 text-lg">
                        🏢
                    </div>
                    <h2 style="color: #1F142B;" class="text-base font-bold mb-1.5">
                        Portal Mitra
                    </h2>
                    <p style="color: #6E5D7E;" class="text-xs leading-relaxed mb-4">
                        Kelola data mahasiswa magang yang diterima, unggah silabus kerja, dan submit penilaian industri.
                    </p>
                </div>

                <div>
                    <div style="background-color: #FAF7FC; color: #4A305A; border-color: #EDE4F5;"
                         class="rounded-lg p-2.5 mb-3.5 text-[11px] border">
                        Akun: <strong>mitra@gmail.com</strong><br>
                        Sandi: <strong>mitra123</strong>
                    </div>
                    <a href="{{ route('login') }}?role=mitra_perusahaan"
                       style="background-color: #690dac; color: #FFFFFF;"
                       class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity">
                        Masuk Mitra →
                    </a>
                </div>
            </div>
        </div>

        <!-- Tech Stack Info -->
        <div style="background-color: #FFFFFF; border-color: #EDE4F5; color: #6E5D7E;"
             class="rounded-xl border py-3 px-4 text-center text-xs">
            Teknologi: <strong>Laravel 12</strong> &bull; <strong>PHP 8.3</strong> &bull; <strong>MySQL (TiDB Cloud)</strong> &bull; <strong>Blade</strong> &bull; <strong>Tailwind CSS</strong> &bull; <strong>DomPDF</strong> &bull; <strong>PDFParser</strong>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer style="color: #8C789C;" class="py-6 text-center text-xs">
        <p class="mb-2">
            &copy; 2026 Pengajuan Magang Mandiri &bull; Dikembangkan oleh <strong>Wahyu Idaya</strong>
        </p>
        <div class="flex justify-center items-center gap-3">
            <a href="https://github.com/wahyuidaya/pengajuan_magang_mandiri" target="_blank" rel="noopener noreferrer" class="hover:underline">
                GitHub
            </a>
            <span>&bull;</span>
            <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="hover:underline">
                Portofolio Pribadi
            </a>
            <span>&bull;</span>
            <a href="{{ route('kampus.beranda') }}" class="hover:underline">
                Informasi Kampus & Mitra
            </a>
        </div>
    </footer>
</body>

</html>
