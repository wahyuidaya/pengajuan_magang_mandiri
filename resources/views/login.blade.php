<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Pengajuan Magang</title>
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

<body class="bg-white font-sans flex items-center justify-center min-h-screen">
<div class="w-full max-w-sm p-6">
    <div class="text-center mb-8">
        <div class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-15 h-15 leading-none text-xl mx-auto mb-4">
            UG</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Sistem Pengajuan Magang</h1>
        <p class="text-sm text-gray-500">Universitas Gunadarma</p>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200" x-data="{ user_type: 'mahasiswa' }">
        <form method="POST" action="/login">
            @csrf
            <div id="server-error" data-message="{{ $errors->first() }}"></div>
            <div id="server-success" data-message="{{ session('success') }}"></div>
            
            <div class="mb-5">
                <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">Masuk sebagai</label>
                <div class="relative">
                    <select id="user_type" name="user_type" required x-model="user_type"
                        class="w-full appearance-none px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus-ug-ring text-sm bg-white">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="mitra_perusahaan">Mitra Perusahaan</option>
                        <option value="staf_prodi">Staf Prodi</option>
                        <option value="dosen_pembimbing">Dosen Pembimbing</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    <span x-show="user_type === 'mahasiswa'">Email atau NPM</span>
                    <span x-show="user_type === 'staf_prodi' || user_type === 'mitra_perusahaan'">Email</span>
                    <span x-show="user_type === 'dosen_pembimbing'">Email</span>
                </label>
                <input type="text" id="username" name="username" 
                    :placeholder="user_type === 'mahasiswa' ? 'Masukkan Email atau NPM' : (user_type === 'dosen_pembimbing' ? 'Masukkan Email' : 'Masukkan email Anda')"
                    required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
            </div>

            <div class="mb-5" x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password"
                        placeholder="Masukkan password Anda" oninput="handleVisibility(this)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex... items-center text-gray-500 hover:text-ug-primary hidden">
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
                class="w-full bg-ug-primary text-white py-2.5 rounded-lg font-semibold transition duration-200 focus:outline-none focus:ring-2 focus-ug-ring focus:ring-offset-2 hover:bg-ug-primary">
                Masuk
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="/lupa_password" class="text-sm text-ug-primary hover:text-[#570992] font-medium">
                Lupa Password?
            </a>
        </div>
        <div class="text-center mt-4 text-sm text-gray-600">
            Belum punya akun?
            <a href="/register" class="text-ug-primary hover:text-[#570992] font-medium">Daftar dulu</a>
        </div>
    </div>
    <div class="text-center mt-6">
        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition duration-150">
            Kembali ke Beranda
        </a>
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

            if (input.value.length > 0) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }

        function toggleProfilePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const activeElem = document.activeElement;

                // List tag yang kita ingin navigasikan pakai Enter
                const validTags = ['INPUT', 'TEXTAREA', 'SELECT'];

                if (validTags.includes(activeElem.tagName)) {
                    const form = activeElem.form;
                    if (!form) return;

                    // Ambil semua elemen inputable termasuk SELECT
                    // Kecuali yang readonly atau disabled
                    const inputs = Array.from(form.querySelectorAll(
                        'input:not([readonly]):not([disabled]), textarea:not([readonly]), select:not([disabled])'
                    ));

                    const index = inputs.indexOf(activeElem);

                    // Jika bukan elemen terakhir (dalam hal ini SELECT), pindah fokus
                    if (index > -1 && index < inputs.length - 1) {
                        e.preventDefault(); // Stop submit
                        inputs[index + 1].focus();
                    }
                    // Jika sudah di SELECT (elemen terakhir), biarkan Enter melakukan Submit
                    else {
                        // Biarkan default browser (Submit form)
                    }
                }
            }
        });
    </script>

</body>

</html>