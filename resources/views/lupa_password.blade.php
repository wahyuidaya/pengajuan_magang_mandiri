<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Magang</title>
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
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-6">
    <div class="flex flex-col items-center">
        <div
            class="bg-[#690dac] text-white font-bold rounded-lg flex justify-center items-center w-15 h-15 leading-none text-xl mx-auto mb-4">
            UG</div>
    </div>
    <div class="w-full max-w-sm">
        <div class="flex flex-col items-center text-center mb-8">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Lupa Password?</h1>
            <p class="text-base text-gray-600 max-w-xs">
                Masukkan email terdaftar Anda untuk menerima instruksi pemulihan password
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-xl p-8 space-y-6">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                        Email Terdaftar
                    </label>
                    <div>
                        <input id="email" name="email" type="email" required autofocus autocomplete="email"
                            placeholder="Masukkan email terdaftar"
                            class="appearance-none block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus-ug-ring text-base transition duration-150 ease-in-out"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-ug-primary hover:bg-ug-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus-ug-ring transition duration-150 ease-in-out">
                        Kirim Instruksi Reset
                    </button>
                </div>
            </form>
            <div class="text-center pt-2">
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-ug-primary text-ug-primary:hover transition duration-150 ease-in-out">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
    <div class="mt-8 text-center">
        <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-900 transition duration-150 ease-in-out">
            Kembali ke Beranda
        </a>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Cek Pesan Sukses (status)
        @if (session('status'))
            alert("Berhasil: {{ session('status') }}");
        @endif

        // 2. Cek Pesan Error (khusus error email)
        @error('email')
            alert("Gagal: {{ $message }}");
        @enderror

        // 3. Cek Error Umum lainnya jika ada
        @if (session('error'))
            alert("Gagal: {{ session('error') }}");
        @endif
    });
</script>
</html>