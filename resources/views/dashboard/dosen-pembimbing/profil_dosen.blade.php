@extends('layouts.app_dosen-pembimbing', ['activeMenu' => 'profil_dosen', 'title' => 'Profil Dosen'])

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-end mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Profil Dosen Pembimbing</h1>
                <p class="text-xs text-gray-500 italic">Kelola identitas dosen dan keamanan akun.</p>
            </div>
        </div>

        {{-- Layout 3 Kolom --}}
        <div class="grid grid-cols-12 gap-4 flex-1 min-h-0">

            {{-- KOLOM 1: IDENTITAS DOSEN --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div
                    class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4 border-2 border-indigo-100 shadow-inner">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <h2 class="text-lg font-bold text-gray-800 leading-tight uppercase">
                        {{ $dosen->nama_dosen }}
                    </h2>

                    <span class="mt-2 px-3 py-1 text-[10px] font-bold rounded-full bg-indigo-100 text-indigo-700">
                        DOSEN PEMBIMBING
                    </span>

                    <div class="mt-4 text-xs text-gray-600 space-y-1">
                        <div>Total Mahasiswa: <strong>{{ $dosen->total_mahasiswa_bimbingan }}</strong></div>
                        <div>Aktif: <strong>{{ $dosen->mahasiswa_aktif }}</strong></div>
                        <div>Selesai: <strong>{{ $dosen->mahasiswa_selesai }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: FORM BIODATA --}}
            <div class="col-span-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h3
                    class="text-[11px] font-black text-indigo-600 uppercase tracking-widest mb-4 border-b pb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Data Dosen
                </h3>

                <form id="formDosen" class="grid grid-cols-2 gap-x-4 gap-y-3 flex-1">
                    @csrf @method('PUT')

                    <div class="col-span-2">
                        <label class="label">Nama Lengkap</label>
                        <input type="text" name="nama_dosen" value="{{ $dosen->nama_dosen }}"
                            class="input bg-gray-50 text-gray-500" readonly>
                    </div>

                    <div class="col-span-2">
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ $dosen->email }}" class="input" required>
                    </div>

                    <div class="col-span-2 mt-auto pt-4">
                        <button type="submit" class="btn-primary">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM 3: PASSWORD --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col">
                    <h3 class="title">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Sandi
                    </h3>

                    <form id="formPassword" class="space-y-3 flex-1 flex flex-col">
                        @csrf @method('PUT')

                        {{-- Sandi Saat Ini --}}
                        <div class="relative w-full">
                            <input type="password" id="current_password" name="current_password" class="input"
                                placeholder="Sandi Saat Ini" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('current_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        {{-- Sandi Baru --}}
                        <div class="relative w-full">
                            <input type="password" id="new_password" name="new_password" class="input"
                                placeholder="Sandi Baru" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        {{-- Ulangi Sandi --}}
                        <div class="relative w-full">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="input" placeholder="Ulangi Sandi" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password_confirmation', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-indigo-600 hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.51-3.703M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" class="btn-dark">
                                GANTI PASSWORD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            margin-left: 4px
        }

        .input {
            width: 100%;
            margin-top: 2px;
            padding: .55rem .9rem;
            border: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 14px;
            font-size: .85rem;
            font-weight: 600
        }

        .input:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .05)
        }

        .btn-primary {
            width: 100%;
            background: #4f46e5;
            color: #fff;
            padding: .75rem;
            border-radius: 16px;
            font-weight: 700
        }

        .btn-dark {
            width: 100%;
            background: #111827;
            color: #fff;
            padding: .6rem;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700
        }

        .title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .1em;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: .5rem;
            display: flex;
            align-items: center
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

    <script>
        /* ==========================
           UPDATE BIODATA DOSEN
        ========================== */
        document.getElementById('formDosen')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'MENYIMPAN...';

            fetch("{{ route('dosen-pembimbing.profil_dosen.update_biodata') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: new FormData(form)
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message);
                    return data;
                })
                .then(data => {
                    alert(data.message || 'Profil berhasil diperbarui');
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Terjadi kesalahan');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        /* ==========================
           UPDATE PASSWORD DOSEN
        ========================== */
        document.getElementById('formPassword')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('new_password_confirmation');

            if (newPassword !== confirmPassword) {
                alert('Konfirmasi password tidak cocok');
                return;
            }

            if (newPassword.length < 8) {
                alert('Password minimal 8 karakter');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'MEMPROSES...';

            fetch("{{ route('dosen-pembimbing.profil_dosen.update_password') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message);
                    return data;
                })
                .then(data => {
                    alert(data.message || 'Password berhasil diubah');
                    form.reset();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Gagal mengubah password');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // Fungsi memunculkan icon saat mengetik
        function handleVisibility(input) {
            const btn = input.parentElement.querySelector('button');
            if (input.value.length > 0) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }

        // Fungsi tukar icon dan tipe input
        function toggleVisibility(inputId, btn) {
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
    </script>


@endsection