<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Universitas Gunadarma</title>
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

        .hover-text-dark:hover {
            color: #570992 !important;
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
            <div
                class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-15 h-15 leading-none text-xl mx-auto mb-4">
                UG</div>
            <h1 class="text-xl font-bold text-gray-800 mb-1">Form Pendaftaran Akun</h1>
            <p class="text-sm text-gray-500">Mahasiswa Universitas Gunadarma</p>
        </div>
        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200">
            <form method="POST" action="/register">
                @csrf

                @if ($errors->any())
                    <script>
                        alert("{{ $errors->first() }}");
                    </script>
                @endif

                @if(session('success'))
                    <script>
                        alert("{{ session('success') }}");
                    </script>
                @endif

                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        placeholder="Masukkan nama lengkap Anda" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm"
                        autocomplete="name">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email Aktif</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email Anda"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm"
                        autocomplete="email">
                </div>

                <div class="mb-4">
                    <label for="npm" class="block text-sm font-medium text-gray-700 mb-1">NPM</label>
                    <input type="text" id="npm" name="npm" value="{{ old('npm') }}" pattern="\d{8,10}"
                        title="NPM harus 8-10 digit" required placeholder="Masukkan NPM Anda (8-10 digit)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
                </div>

                <div class="mb-4">
                    <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="tel" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}" pattern="\d{10,13}"
                        title="Nomor HP harus 10-13 digit" required placeholder="Masukkan nomor HP Anda (10-13 digit)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm">
                </div>

                <div class="mb-4" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                            placeholder="Masukkan password Anda" oninput="handleVisibility(this)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm"
                            autocomplete="new-password">
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

                <div class="mb-6" x-data="{ showConfirm: false }">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                            name="password_confirmation" placeholder="Konfirmasi password Anda"
                            oninput="handleVisibility(this)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-sm"
                            autocomplete="new-password">
                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-3 flex... items-center text-gray-500 hover:text-ug-primary hidden">
                            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-ug-primary text-white py-2.5 rounded-lg font-semibold transition duration-200 focus:outline-none focus:ring-2 focus-ug-ring focus:ring-offset-2 hover:bg-ug-primary">
                    Daftar
                </button>
            </form>
            <div class="text-center mt-4 text-sm text-gray-600">
                Sudah punya akun?
                <a href="/login" class="text-ug-primary hover-text-dark font-medium">Masuk</a>
            </div>
        </div>
        <div class="text-center mt-6">
            <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition duration-150">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

<script>
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

            // Pastikan kita berada di dalam elemen input/textarea
            if (activeElem.tagName === 'INPUT' || activeElem.tagName === 'TEXTAREA') {
                const form = activeElem.form;
                if (!form) return;

                // Ambil semua input yang bisa diisi (tidak readonly/disabled)
                const inputs = Array.from(form.querySelectorAll('input:not([readonly]):not([disabled]), textarea:not([readonly])'));
                const index = inputs.indexOf(activeElem);

                // Jika bukan input terakhir, pindah fokus ke kolom berikutnya
                if (index > -1 && index < inputs.length - 1) {
                    e.preventDefault(); // Mencegah submit
                    inputs[index + 1].focus();
                }
                // Jika ini input terakhir, biarkan perilaku default (Submit Form)
                else {
                    // Jangan panggil e.preventDefault(), maka browser akan otomatis men-submit form tersebut
                }
            }
        }
    });
</script>

</html>