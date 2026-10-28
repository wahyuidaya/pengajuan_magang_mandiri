<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Magang Mandiri - Universitas Gunadarma</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        .bg-ug-primary {
            background-color: #690dac;
        }

        .text-ug-primary {
            color: #690dac;
        }

        .border-ug-primary {
            border-color: #690dac;
        }

        .bg-ug-primary:hover {
            background-color: #570992 !important;
        }

        .hover-dark-bg:hover {
            background-color: #570992;
            border-color: #570992 !important;
            color: white !important;
        }

        .hover\:border-ug-primary:hover {
            border-color: #690dac !important;
        }

        .nav-link:hover {
            color: #690dac !important;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800">
    @include('partials.demo_top_bar')
    <header class="sticky top-0 z-40 shadow-md bg-white">
        <nav class="w-full">
            <div class="container mx-auto px-10 py-3.5 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div
                        class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-10 h-10 leading-none text-lg">
                        UG
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-gray-800 leading-none">Universitas Gunadarma</span>
                        <span class="text-[10px] font-medium text-gray-500 mt-0.5 uppercase tracking-wider">
                            Sistem Informasi
                        </span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8 text-base text-gray-700 font-medium">
                    <a href="#beranda" class="nav-link transition duration-150">Beranda</a>
                    <a href="#demo-portal" class="text-purple-700 font-bold hover:text-purple-900 transition inline-flex items-center gap-1.5 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Coba Demo Portal
                    </a>
                    <a href="#perusahaan" class="nav-link transition duration-150">Perusahaan</a>
                    <a href="#informasi" class="nav-link transition duration-150">Informasi</a>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('register') }}"
                        class="border border-ug-primary py-2 px-5 rounded-lg font-medium text-base text-ug-primary transition duration-200 hover-dark-bg">
                        Daftar
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-ug-primary text-white py-2 px-5 rounded-lg font-medium text-base transition duration-200 hover:shadow-lg">
                        Masuk
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section id="beranda" class="relative overflow-hidden" style="height: 90vh;">
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('gambar/background.png') }}'); background-position: right top;">
                <div class="absolute inset-0 bg-black opacity-10"></div>
            </div>
            <div class="container mx-auto px-10 flex items-center h-full relative z-10">
                <div class="max-w-3xl text-white pt-24">
                    <h1 class="text-5xl font-extrabold leading-snug mb-4">
                        Ajukan Magang Mandiri Anda dengan Mudah dan Cepat
                    </h1>
                    <p class="text-xl font-light mb-10 opacity-90">
                        Platform terpadu untuk mengajukan magang mandiri ke perusahaan pilihan Anda. Proses yang
                        transparan dan efisien.
                    </p>
                    <div class="flex space-x-5">
                        <a href="#demo-portal"
                            class="bg-purple-700 hover:bg-purple-800 text-white text-lg py-3.5 px-8 rounded-md font-semibold transition duration-200 shadow-xl inline-flex items-center gap-2">
                            <span>⚡</span> Coba Live Demo
                        </a>
                        <a href="{{ route('login') }}"
                            class="bg-white text-ug-primary text-lg py-3.5 px-8 rounded-md font-semibold border border-white transition duration-200 hover:bg-gray-100">
                            Masuk ke Sistem
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- LIVE DEMO PORTAL SECTION - PORTOFOLIO WAHYU IDAYA -->
        <section id="demo-portal" class="py-16 bg-gradient-to-b from-purple-50/70 via-white to-gray-50 border-y border-purple-200">
            <div class="container mx-auto px-6 md:px-10">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-900 border border-purple-200 mb-3 tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        LIVE DEMO EXPLORER &bull; PORTOFOLIO WAHYU IDAYA
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">
                        Uji Coba Sistem Pengajuan Magang Mandiri
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Sistem ini dikembangkan secara tim (SDLC) di mana saya berperan sebagai <strong>Programmer Backend & Integrasi Sistem</strong>. Pilih salah satu portal di bawah ini untuk langsung mencoba alur kerja sesuai perannya:
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- 1. Mahasiswa -->
                    <div class="bg-white rounded-2xl border-2 border-purple-100 hover:border-purple-400 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-200 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                🎓
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Peran 01</span>
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">Portal Mahasiswa</h3>
                            <p class="text-gray-600 text-xs leading-relaxed mb-4">
                                Ajukan magang mandiri, upload berkas KRS (ekstraksi data otomatis via PDFParser), dan pantau progres bimbingan.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="bg-purple-50/70 rounded-lg p-2.5 mb-3 text-[11px] text-gray-700 border border-purple-100">
                                <div>Email: <strong class="text-purple-900 font-mono">mahasiswa@gmail.com</strong></div>
                                <div>Sandi: <strong class="text-purple-900 font-mono">mahasiswa123</strong></div>
                            </div>
                            <a href="{{ route('login') }}?role=mahasiswa"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-purple-700 hover:bg-purple-800 transition shadow-sm">
                                Coba Masuk Mahasiswa →
                            </a>
                        </div>
                    </div>

                    <!-- 2. Staf Prodi -->
                    <div class="bg-white rounded-2xl border-2 border-purple-100 hover:border-purple-400 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-200 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                🏛️
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Peran 02</span>
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">Portal Staf Prodi</h3>
                            <p class="text-gray-600 text-xs leading-relaxed mb-4">
                                Verifikasi berkas kelayakan magang, lakukan ploting dosen pembimbing, dan cetak surat pengantar otomatis via DomPDF.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="bg-indigo-50/70 rounded-lg p-2.5 mb-3 text-[11px] text-gray-700 border border-indigo-100">
                                <div>Email: <strong class="text-indigo-900 font-mono">stafprodi@gmail.com</strong></div>
                                <div>Sandi: <strong class="text-indigo-900 font-mono">stafprodi123</strong></div>
                            </div>
                            <a href="{{ route('login') }}?role=staf_prodi"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-indigo-700 hover:bg-indigo-800 transition shadow-sm">
                                Coba Masuk Staf Prodi →
                            </a>
                        </div>
                    </div>

                    <!-- 3. Dosen Pembimbing -->
                    <div class="bg-white rounded-2xl border-2 border-purple-100 hover:border-purple-400 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-200 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                👨‍🏫
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Peran 03</span>
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">Dosen Pembimbing</h3>
                            <p class="text-gray-600 text-xs leading-relaxed mb-4">
                                Monitoring daftar mahasiswa bimbingan, review laporan logbook mingguan, dan evaluasi hasil magang mahasiswa.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="bg-blue-50/70 rounded-lg p-2.5 mb-3 text-[11px] text-gray-700 border border-blue-100">
                                <div>Email: <strong class="text-blue-900 font-mono">dosen@gmail.com</strong></div>
                                <div>Sandi: <strong class="text-blue-900 font-mono">dosen123</strong></div>
                            </div>
                            <a href="{{ route('login') }}?role=dosen_pembimbing"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-sm">
                                Coba Masuk Dosen →
                            </a>
                        </div>
                    </div>

                    <!-- 4. Mitra Perusahaan -->
                    <div class="bg-white rounded-2xl border-2 border-purple-100 hover:border-purple-400 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-200 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                                🏢
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded">Peran 04</span>
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">Mitra Perusahaan</h3>
                            <p class="text-gray-600 text-xs leading-relaxed mb-4">
                                Manajemen mahasiswa magang yang diterima di perusahaan, upload silabus kerja, dan submit penilaian industri.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="bg-teal-50/70 rounded-lg p-2.5 mb-3 text-[11px] text-gray-700 border border-teal-100">
                                <div>Email: <strong class="text-teal-900 font-mono">mitra@gmail.com</strong></div>
                                <div>Sandi: <strong class="text-teal-900 font-mono">mitra123</strong></div>
                            </div>
                            <a href="{{ route('login') }}?role=mitra_perusahaan"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-800 transition shadow-sm">
                                Coba Masuk Mitra →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center flex flex-wrap items-center justify-center gap-4">
                    <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-purple-300 text-purple-900 rounded-xl text-sm font-bold hover:bg-purple-50 transition shadow-sm">
                        <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        ← Kembali ke Portofolio Utama Wahyu Idaya
                    </a>
                    <a href="https://github.com/wahyuidaya/pengajuan_magang_mandiri" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        Lihat Kode di GitHub
                    </a>
                </div>
            </div>
        </section>

        <!-- SECTION PERUSAHAAN - DARI DATABASE -->
        <section id="perusahaan" class="py-20 md:py-28 bg-white">
            <div class="container mx-auto px-10 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                    Perusahaan Mitra Kami
                </h2>
                <p class="text-lg text-gray-600 mb-14 max-w-4xl mx-auto">
                    Bergabunglah dengan ribuan mahasiswa yang telah melakukan magang di perusahaan-perusahaan terkemuka
                </p>

                @if($companies->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                        @php
                            $colors = [
                                'bg-blue-600',
                                'bg-green-600',
                                'bg-indigo-600',
                                'bg-orange-600',
                                'bg-red-600',
                                'bg-purple-600',
                                'bg-teal-600',
                                'bg-pink-600'
                            ];
                        @endphp

                        @foreach($companies as $index => $company)
                            @php
                                $color = $colors[$index % count($colors)];
                                $initial = strtoupper(substr($company->nama_perusahaan, 0, 1));
                            @endphp
                            <div
                                class="h-32 rounded-lg text-white shadow-xl flex flex-col justify-center items-center transition duration-300 hover:shadow-2xl cursor-default {{ $color }}">
                                <div class="flex flex-col justify-center items-center h-full px-3">
                                    <div class="bg-white rounded-md w-12 h-12 flex justify-center items-center mb-2">
                                        <p class="text-xl font-extrabold text-gray-800">{{ $initial }}</p>
                                    </div>
                                    <p class="font-semibold text-sm text-center leading-tight">
                                        {{ Str::limit($company->nama_perusahaan, 30) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Lihat Semua Perusahaan -->
                    <div class="mt-12">
                        <a href="{{ route('perusahaan.index') }}"
                            class="inline-block bg-ug-primary text-white text-lg py-3 px-8 rounded-lg font-semibold transition duration-200 hover:shadow-xl">
                            Lihat Semua Perusahaan
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">Belum ada perusahaan mitra yang terdaftar</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- SECTION FAQ - DARI DATABASE -->
<section id="informasi" class="py-20 md:py-28 bg-gray-50">
    <div class="container mx-auto px-10">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                Pusat Informasi & FAQ
            </h2>
            <p class="text-lg text-gray-600">
                Temukan informasi lengkap dan jawaban atas pertanyaan umum seputar prosedur pengajuan magang.
            </p>
        </div>

        @if($faqs->count() > 0)
            <div class="max-w-4xl mx-auto space-y-4">
                @foreach($faqs as $faq)
                    <div x-data="{ open: false }"
                        class="rounded-lg border transition-all duration-300 cursor-pointer hover:border-ug-primary"
                        :class="open ? 'border-ug-primary bg-white shadow-md' : 'border-gray-200 bg-white'">
                        <button @click="open = !open"
                            class="cursor-pointer flex justify-between items-center w-full p-6 text-left text-lg font-semibold transition duration-200"
                            :class="open ? 'text-ug-primary' : 'text-gray-800'">
                            <span>{{ $faq->pertanyaan }}</span>
                            <svg class="w-6 h-6 transform transition-transform duration-300"
                                :class="open ? 'rotate-180 text-ug-primary' : 'rotate-0 text-gray-500'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse.duration.500ms class="px-6 pb-6 text-gray-700 leading-relaxed">
                            {{ $faq->jawaban }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Informasi pertanyaan umum belum tersedia.</p>
            </div>
        @endif

        <div class="mt-16 max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-3">Masih Memiliki Pertanyaan?</h3>
            <p class="text-gray-600 mb-6">Jika memerlukan informasi lebih lanjut atau bantuan teknis lainnya, silakan hubungi layanan konsultasi Staf Prodi melalui tautan di bawah ini.</p>
            
            <a href="https://wa.me/6282127453186?text=Yth.%20Staf%20Prodi,%20perkenalkan%20saya%20(Sebutkan%20Nama/NPM).%20Saya%20ingin%20berkonsultasi%20mengenai%20prosedur%20pengajuan%20magang." 
               target="_blank"
               class="inline-flex items-center justify-center px-8 py-3 bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 0 5.414 0 12.05c0 2.123.553 4.197 1.603 6.02L0 24l6.132-1.608a11.845 11.845 0 005.914 1.57h.004c6.637 0 12.05-5.414 12.05-12.05 0-3.213-1.25-6.233-3.521-8.503z"/>
                </svg>
                Layanan Bantuan Staf
            </a>
        </div>
    </div>
</section>
    </main>

    <footer class="bg-gray-950 text-white py-12 border-t border-purple-900/40">
        <div class="container mx-auto px-10 text-center">
            <div class="flex flex-wrap justify-center items-center gap-6 text-xs text-gray-300 mb-6">
                <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="hover:text-purple-300 font-bold transition inline-flex items-center gap-1.5 bg-purple-900/50 border border-purple-500/40 px-3.5 py-1.5 rounded-full text-white shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Portofolio Wahyu Idaya
                </a>
                <a href="https://github.com/wahyuidaya/pengajuan_magang_mandiri" target="_blank" rel="noopener noreferrer" class="hover:text-purple-300 transition inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                    GitHub Repository
                </a>
                <a href="{{ route('login') }}" class="hover:text-purple-300 transition">
                    Halaman Login Demo
                </a>
            </div>
            <p class="text-xs text-gray-400">
                &copy; 2026 <strong>Wahyu Idaya</strong> &bull; Sistem Pengajuan Magang Mandiri Universitas Gunadarma &bull; Live Demo Portfolio
            </p>
        </div>
    </footer>
</body>

</html>