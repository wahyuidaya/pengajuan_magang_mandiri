<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Perusahaan Mitra - Universitas Gunadarma</title>
    @vite('resources/css/app.css')
    <style>
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

        .nav-link:hover {
            color: #690dac !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <header class="sticky top-0 z-50 shadow-md bg-white">
        <nav class="w-full">
            <div class="container mx-auto px-10 py-3.5 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div
                        class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-10 h-10 leading-none text-lg">
                        UG</div>
                    <span class="text-lg font-semibold text-gray-800">Universitas Gunadarma</span>
                </div>
                <div class="hidden md:flex items-center space-x-10 text-base text-gray-700 font-medium">
                    <a href="/" class="nav-link transition duration-150">Beranda</a>
                    <a href="{{ route('perusahaan.index') }}"
                        class="nav-link transition duration-150 text-ug-primary font-bold">Perusahaan</a>
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

    <main class="py-12 min-h-screen">
        <div class="container mx-auto px-10">
            <!-- Header -->
            <div class="mb-6">
                <a href="/#perusahaan" class="inline-flex items-center text-ug-primary hover:underline mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="mb-12 text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-3">
                    Semua Perusahaan Mitra
                </h1>
                <p class="text-lg text-gray-600">
                    Total <span class="font-semibold text-ug-primary">{{ $companies->count() }}</span> perusahaan mitra
                    yang telah terverifikasi
                </p>
            </div>

            @if($companies->count() > 0)
                <!-- Grid Perusahaan -->
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @php
                        $colors = [
                            'bg-blue-600',
                            'bg-green-600',
                            'bg-indigo-600',
                            'bg-orange-600',
                            'bg-red-600',
                            'bg-purple-600',
                            'bg-teal-600',
                            'bg-pink-600',
                            'bg-cyan-600',
                            'bg-yellow-600',
                            'bg-lime-600',
                            'bg-rose-600'
                        ];
                    @endphp

                    @foreach($companies as $index => $company)
                        @php
                            $color = $colors[$index % count($colors)];
                            $initial = strtoupper(substr($company->nama_perusahaan, 0, 1));
                        @endphp
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                            <!-- Header Card dengan Warna -->
                            <div class="{{ $color }} h-32 flex justify-center items-center">
                                <div class="bg-white rounded-md w-16 h-16 flex justify-center items-center mb-2">
                                    <p class="text-2xl font-extrabold text-gray-800">{{ $initial }}</p>
                                </div>
                            </div>

                            <!-- Content Card -->
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 min-h-14 line-clamp-2">
                                    {{ $company->nama_perusahaan }}
                                </h3>

                                <div class="space-y-2 text-sm text-gray-600">
                                    @if($company->alamat)
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5 shrink-0" ... fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="line-clamp-2">{{ $company->alamat }}</span>
                                        </div>
                                    @endif

                                    @if($company->kontak_pic)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 shrink-0" ... fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="truncate">{{ $company->kontak_pic }}</span>
                                        </div>
                                    @endif

                                    @if($company->no_telp_pic)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 shrink-0" ... fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="truncate">{{ $company->no_telp_pic }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Terverifikasi
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Perusahaan Mitra</h3>
                    <p class="text-gray-600">Saat ini belum ada perusahaan mitra yang terdaftar dan terverifikasi.</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-ug-primary py-6 mt-12">
        <div class="container mx-auto px-10 text-center">
            <p class="text-white text-sm">
                © 2025 Universitas Gunadarma - Sistem Pengajuan Magang Mandiri
            </p>
        </div>
    </footer>
</body>

</html>