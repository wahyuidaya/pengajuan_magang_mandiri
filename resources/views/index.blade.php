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
    <header class="sticky top-0 z-50 shadow-md bg-white">
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
                <div class="hidden md:flex items-center space-x-10 text-base text-gray-700 font-medium">
                    <a href="#beranda" class="nav-link transition duration-150">Beranda</a>
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
                style="background-image: url('{{ asset('storage/gambar/background.png') }}'); background-position: right top;">
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
                        <a href="{{ route('login') }}"
                            class="bg-ug-primary text-white text-lg py-3.5 px-8 rounded-md font-semibold transition duration-200 hover:shadow-xl">
                            Ajukan Sekarang
                        </a>
                        <a href="#informasi"
                            class="bg-white text-ug-primary text-lg py-3.5 px-8 rounded-md font-semibold border border-white transition duration-200 hover:bg-gray-100">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
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

    <footer class="bg-ug-primary py-6">
        <div class="container mx-auto px-10 text-center">
            <p class="text-white text-sm">
                © 2025 Universitas Gunadarma - Sistem Pengajuan Magang Mandiri
            </p>
        </div>
    </footer>
</body>

</html>