<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Pengajuan Magang (Live Demo)</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        .focus-ug-ring:focus {
            --tw-ring-color: #690dac !important;
            border-color: #690dac !important;
        }

        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            appearance: none !important;
        }

        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
            visibility: hidden !important;
            pointer-events: none !important;
            position: absolute !important;
            right: 0 !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    @include('partials.demo_top_bar')

    <div class="flex-1 flex items-center justify-center p-4 py-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <div class="bg-[#690dac] text-white font-bold rounded-xl flex justify-center items-center w-14 h-14 leading-none text-xl mx-auto mb-3 shadow-md">
                    UG
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Sistem Pengajuan Magang Mandiri</h1>
                <p class="text-xs text-gray-500 mt-0.5">Program Studi Sistem Informasi &bull; Universitas Gunadarma</p>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold mt-2 border border-purple-200">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    Portfolio Live Demo &bull; Karya Wahyu Idaya
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-200" 
                 x-data="{ 
                     user_type: new URLSearchParams(window.location.search).get('role') || 'mahasiswa',
                     fillDemo(role, username, password) {
                         this.user_type = role;
                         const select = document.getElementById('user_type');
                         if (select) select.value = role;
                         const uInput = document.getElementById('username');
                         if (uInput) uInput.value = username;
                         const pInput = document.getElementById('password');
                         if (pInput) {
                             pInput.value = password;
                             const btn = pInput.parentElement.querySelector('button');
                             if (btn) btn.classList.remove('hidden');
                         }
                     },
                     init() {
                         const params = new URLSearchParams(window.location.search);
                         const r = params.get('role');
                         if (r === 'mahasiswa') this.fillDemo('mahasiswa', 'mahasiswa@gmail.com', 'mahasiswa123');
                         else if (r === 'staf_prodi') this.fillDemo('staf_prodi', 'stafprodi@gmail.com', 'stafprodi123');
                         else if (r === 'dosen_pembimbing') this.fillDemo('dosen_pembimbing', 'dosen@gmail.com', 'dosen123');
                         else if (r === 'mitra_perusahaan') this.fillDemo('mitra_perusahaan', 'mitra@gmail.com', 'mitra123');
                     }
                 }">

                <!-- QUICK FILL DEMO BOX -->
                <div class="mb-5 p-3.5 bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-bold text-purple-900 flex items-center gap-1">
                            ⚡ Klik Akun Demo (Isi Otomatis):
                        </span>
                        <span class="text-[10px] bg-purple-200/80 text-purple-800 font-semibold px-2 py-0.5 rounded">
                            4 Role Tersedia
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="fillDemo('mahasiswa', 'mahasiswa@gmail.com', 'mahasiswa123')"
                            :class="user_type === 'mahasiswa' ? 'bg-purple-700 text-white shadow-sm ring-2 ring-purple-400' : 'bg-white text-gray-700 border border-purple-200 hover:bg-purple-100/70'"
                            class="p-2 rounded-lg text-left transition cursor-pointer">
                            <div class="font-bold text-xs flex items-center gap-1">🎓 Mahasiswa</div>
                            <div class="text-[10px] opacity-80 truncate">mahasiswa@gmail.com</div>
                            <div class="text-[9px] opacity-70">Pass: mahasiswa123</div>
                        </button>

                        <button type="button" @click="fillDemo('staf_prodi', 'stafprodi@gmail.com', 'stafprodi123')"
                            :class="user_type === 'staf_prodi' ? 'bg-purple-700 text-white shadow-sm ring-2 ring-purple-400' : 'bg-white text-gray-700 border border-purple-200 hover:bg-purple-100/70'"
                            class="p-2 rounded-lg text-left transition cursor-pointer">
                            <div class="font-bold text-xs flex items-center gap-1">🏛️ Staf Prodi</div>
                            <div class="text-[10px] opacity-80 truncate">stafprodi@gmail.com</div>
                            <div class="text-[9px] opacity-70">Pass: stafprodi123</div>
                        </button>

                        <button type="button" @click="fillDemo('dosen_pembimbing', 'dosen@gmail.com', 'dosen123')"
                            :class="user_type === 'dosen_pembimbing' ? 'bg-purple-700 text-white shadow-sm ring-2 ring-purple-400' : 'bg-white text-gray-700 border border-purple-200 hover:bg-purple-100/70'"
                            class="p-2 rounded-lg text-left transition cursor-pointer">
                            <div class="font-bold text-xs flex items-center gap-1">👨‍🏫 Dosen</div>
                            <div class="text-[10px] opacity-80 truncate">dosen@gmail.com</div>
                            <div class="text-[9px] opacity-70">Pass: dosen123</div>
                        </button>

                        <button type="button" @click="fillDemo('mitra_perusahaan', 'mitra@gmail.com', 'mitra123')"
                            :class="user_type === 'mitra_perusahaan' ? 'bg-purple-700 text-white shadow-sm ring-2 ring-purple-400' : 'bg-white text-gray-700 border border-purple-200 hover:bg-purple-100/70'"
                            class="p-2 rounded-lg text-left transition cursor-pointer">
                            <div class="font-bold text-xs flex items-center gap-1">🏢 Mitra</div>
                            <div class="text-[10px] opacity-80 truncate">mitra@gmail.com</div>
                            <div class="text-[9px] opacity-70">Pass: mitra123</div>
                        </button>
                    </div>
                </div>

                <form method="POST" action="/login">
                    @csrf
                    <div id="server-error" data-message="{{ $errors->first() }}"></div>
                    <div id="server-success" data-message="{{ session('success') }}"></div>
                    
                    <div class="mb-4">
                        <label for="user_type" class="block text-xs font-semibold text-gray-700 mb-1">Masuk sebagai</label>
                        <div class="relative">
                            <select id="user_type" name="user_type" required x-model="user_type"
                                class="w-full appearance-none px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus-ug-ring text-sm bg-white">
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="staf_prodi">Staf Prodi</option>
                                <option value="dosen_pembimbing">Dosen Pembimbing</option>
                                <option value="mitra_perusahaan">Mitra Perusahaan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-xs font-semibold text-gray-700 mb-1">
                            <span x-show="user_type === 'mahasiswa'">Email atau NPM</span>
                            <span x-show="user_type === 'staf_prodi' || user_type === 'mitra_perusahaan'">Email</span>
                            <span x-show="user_type === 'dosen_pembimbing'">Email</span>
                        </label>
                        <input type="text" id="username" name="username" 
                            :placeholder="user_type === 'mahasiswa' ? 'Masukkan Email atau NPM' : (user_type === 'dosen_pembimbing' ? 'Masukkan Email' : 'Masukkan email Anda')"
                            required autofocus
                            class="w-full px-3.5 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
                    </div>

                    <div class="mb-5" x-data="{ show: false }">
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" id="password" name="password"
                                placeholder="Masukkan password Anda" oninput="handleVisibility(this)"
                                class="w-full px-3.5 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-ug-primary hidden">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-ug-primary text-white py-2.5 rounded-lg font-semibold transition duration-200 focus:outline-none focus:ring-2 focus-ug-ring focus:ring-offset-2 hover:bg-ug-primary shadow-md hover:shadow-lg">
                        Masuk ke Portal Demo →
                    </button>
                </form>

                <div class="text-center mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <a href="/" class="hover:text-ug-primary transition">← Halaman Depan</a>
                    <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="text-purple-700 font-semibold hover:underline">Portofolio Wahyu Idaya ↗</a>
                </div>
            </div>

            <div class="text-center mt-6 text-xs text-gray-500">
                <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="hover:underline">
                    &copy; 2026 Wahyu Idaya &bull; Proyek Portofolio Pengajuan Magang Mandiri
                </a>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const errorMsg = document.getElementById('server-error').dataset.message;
            const successMsg = document.getElementById('server-success').dataset.message;

            if (errorMsg) {
                alert(errorMsg);
            }

            if (successMsg) {
                alert(successMsg);
            }
        });

        function handleVisibility(input) {
            const btn = input.parentElement.querySelector('button');
            if (!btn) return;
            if (input.value.length > 0) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
