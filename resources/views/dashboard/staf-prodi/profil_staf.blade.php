@extends('layouts.app_staf-prodi', ['activeMenu' => 'profil', 'title' => 'Profil Staf'])

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-end mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Profil Akun</h1>
                <p class="text-xs text-gray-500 italic">Kelola identitas staf prodi dan keamanan akun.</p>
            </div>
        </div>

        {{-- Layout 3 Kolom --}}
        <div class="grid grid-cols-12 gap-4 flex-1 min-h-0">

            {{-- KOLOM 1: IDENTITAS VISUAL --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4 border-2 border-indigo-100 shadow-inner">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <h2 class="text-lg font-bold text-gray-800 leading-tight uppercase">
                        {{ auth()->user()->username }}
                    </h2>

                    <span class="mt-2 px-3 py-1 text-[10px] font-bold rounded-full bg-indigo-100 text-indigo-700">
                        STAF PRODI
                    </span>

                    <div class="mt-4 text-xs text-gray-400 italic">
                        Terdaftar sejak: <br>
                        <strong>{{ auth()->user()->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: FORM BIODATA (EMAIL) --}}
            <div class="col-span-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h3 class="text-[11px] font-black text-indigo-600 uppercase tracking-widest mb-4 border-b pb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Data Akun
                </h3>

                <form id="formProfil" class="grid grid-cols-2 gap-x-4 gap-y-3 flex-1">
                    @csrf @method('PUT')

                    <div class="col-span-2">
                        <label class="label">Username (Tetap)</label>
                        <input type="text" value="{{ auth()->user()->username }}"
                            class="input bg-gray-50 text-gray-400 cursor-not-allowed" readonly title="Username tidak dapat diubah">
                    </div>

                    <div class="col-span-2">
                        <label class="label">Alamat Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="input" required>
                    </div>

                    <div class="col-span-2 mt-auto pt-4">
                        <button type="submit" class="btn-primary uppercase tracking-widest text-xs">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM 3: FORM PASSWORD --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col">
                    <h3 class="title">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Keamanan
                    </h3>

                    <form id="formPassword" class="space-y-3 flex-1 flex flex-col mt-4">
                        @csrf @method('PUT')

                        {{-- Sandi Saat Ini --}}
                        <div class="relative w-full">
                            <input type="password" id="current_password" name="current_password" class="input"
                                placeholder="Sandi Saat Ini" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('current_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        {{-- Sandi Baru --}}
                        <div class="relative w-full">
                            <input type="password" id="new_password" name="new_password" class="input"
                                placeholder="Sandi Baru" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        {{-- Ulangi Sandi --}}
                        <div class="relative w-full">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="input" placeholder="Ulangi Sandi" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password_confirmation', this)"
                                class="absolute inset-y-0 right-3 fle..x items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" class="btn-dark uppercase text-[10px] tracking-widest">
                                GANTI PASSWORD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .label { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-left: 4px; }
        .input { width: 100%; margin-top: 2px; padding: .55rem .9rem; border: 1px solid #f1f5f9; background: #f8fafc; border-radius: 14px; font-size: .85rem; font-weight: 600; transition: all 0.2s; }
        .input:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 4px rgba(99, 102, 241, .05); }
        .btn-primary { width: 100%; background: #4f46e5; color: #fff; padding: .75rem; border-radius: 16px; font-weight: 700; transition: all 0.2s; }
        .btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .btn-dark { width: 100%; background: #111827; color: #fff; padding: .6rem; border-radius: 14px; font-size: 11px; font-weight: 700; transition: all 0.2s; }
        .btn-dark:hover { background: #000; }
        .title { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .1em; border-bottom: 1px solid #e5e7eb; padding-bottom: .5rem; display: flex; align-items: center; }
        input[type="password"]::-ms-reveal, input[type="password"]::-ms-clear { display: none !important; }
    </style>

    <script>
        /* UPDATE PROFIL (EMAIL) */
        document.getElementById('formProfil')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const original = btn.textContent;
            btn.disabled = true; btn.textContent = 'MENYIMPAN...';

            fetch("{{ route('profil.email.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: new FormData(this)
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message);
                alert(data.message || 'Email berhasil diperbarui');
            })
            .catch(err => alert(err.message))
            .finally(() => { btn.disabled = false; btn.textContent = original; });
        });

        /* UPDATE PASSWORD */
        document.getElementById('formPassword')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            if (formData.get('new_password') !== formData.get('new_password_confirmation')) {
                return alert('Konfirmasi password tidak cocok');
            }
            
            const btn = this.querySelector('button[type="submit"]');
            const original = btn.textContent;
            btn.disabled = true; btn.textContent = 'MEMPROSES...';

            fetch("{{ route('profil.password.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message);
                alert(data.message || 'Password berhasil diubah');
                this.reset();
            })
            .catch(err => alert(err.message))
            .finally(() => { btn.disabled = false; btn.textContent = original; });
        });

        function handleVisibility(input) {
            const btn = input.parentElement.querySelector('button');
            input.value.length > 0 ? btn.classList.remove('hidden') : btn.classList.add('hidden');
        }

        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.querySelector('.eye-open').classList.toggle('hidden', isPassword);
            btn.querySelector('.eye-closed').classList.toggle('hidden', !isPassword);
        }
    </script>
@endsection