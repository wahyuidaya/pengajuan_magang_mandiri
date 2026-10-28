<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Magang</title>
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

        .text-ug-primary:hover {
            color: #570992 !important;
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

<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-6">

    {{-- LOGO --}}
    <div class="flex flex-col items-center">
        <div
            class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-15 h-15 leading-none text-xl mx-auto mb-4">
            UG</div>
    </div>

    <div class="w-full max-w-sm">
        {{-- HEADER --}}
        <div class="flex flex-col items-center text-center mb-8">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">
                Reset Password
            </h1>
            <p class="text-base text-gray-600 max-w-xs">
                Silakan masukkan password baru untuk akun Anda
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-xl shadow-xl p-8 space-y-6">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                {{-- TOKEN --}}
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                {{-- PASSWORD BARU --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" oninput="handleVisibility(this)"
                            placeholder="Masukkan password baru"
                            class="password-input appearance-none block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus-ug-ring transition">

                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-ug-primary hidden">
                            <svg class="eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- KONFIRMASI PASSWORD --}}
                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            oninput="handleVisibility(this)" placeholder="Ulangi password baru"
                            class="password-input appearance-none block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus-ug-ring transition">

                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-ug-primary hidden">
                            <svg class="eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg shadow-sm
                        text-base font-medium text-white bg-ug-primary hover:bg-ug-primary
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus-ug-ring transition">
                        Simpan Password
                    </button>
                </div>
            </form>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-ug-primary hover:text-[#570992] transition">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="mt-8 text-center">
        <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
            Kembali ke Beranda
        </a>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

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
</body>

</html>