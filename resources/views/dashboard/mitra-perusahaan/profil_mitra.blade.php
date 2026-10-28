@extends('layouts.app_mitra-perusahaan', ['activeMenu' => 'profil_mitra', 'title' => 'Profil Perusahaan'])

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Header Ringkas --}}
        <div class="flex justify-between items-end mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Profil Mitra</h1>
                <p class="text-xs text-gray-500 italic">Kelola informasi identitas perusahaan dan keamanan akun.</p>
            </div>
        </div>

        {{-- Layout Utama (3 Kolom Horizontal) --}}
        <div class="grid grid-cols-12 gap-4 flex-1 min-h-0">

            {{-- KOLOM 1: IDENTITAS PERUSAHAAN (Visual) --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div
                    class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-24 h-24 bg-blue-50 rounded-2xl flex items-center justify-center mb-4 border-2 border-blue-100 shadow-inner">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800 leading-tight uppercase">{{ $mitra->nama_perusahaan }}</h2>
                    <span
                        class="mt-2 px-3 py-1 text-[10px] font-bold rounded-full
                        {{ $mitra->status_verifikasi === 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $mitra->status_label }}
                    </span>
                </div>
            </div>

            {{-- KOLOM 2: FORM DATA MITRA (Terbesar) --}}
            <div class="col-span-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <h3
                    class="text-[11px] font-black text-blue-600 uppercase tracking-widest mb-4 border-b pb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Data Perusahaan
                </h3>

                <form id="formMitra" class="grid grid-cols-2 gap-x-4 gap-y-3 flex-1">
                    @csrf @method('PUT')
                    {{-- Nama Perusahaan (READ ONLY) --}}
                    <div class="col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Nama Perusahaan</label>
                        <input type="text" value="{{ $mitra->nama_perusahaan }}"
                            class="input-mitra bg-gray-50 text-gray-500" readonly>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">No. Telp</label>
                        <input type="text" name="no_telp_pic" value="{{ $mitra->no_telp_pic }}" class="input-mitra"
                            required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Email</label>
                        <input type="email" name="email_pic" value="{{ $mitra->email_pic }}" class="input-mitra" required>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Alamat Perusahaan</label>
                        <textarea name="alamat" rows="2" class="input-mitra resize-none">{{ $mitra->alamat }}</textarea>
                    </div>

                    <div class="col-span-2 mt-auto pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 active:scale-95">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM 3: KEAMANAN (Password) --}}
            <div class="col-span-3 flex flex-col gap-4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex-1 flex flex-col">
                    <h3
                        class="text-[11px] font-black text-gray-800 uppercase tracking-widest mb-4 border-b pb-2 flex items-center">
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
                            <input type="password" id="current_password" name="current_password" class="input-mitra"
                                placeholder="Sandi Saat Ini" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('current_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-blue-600 hidden">
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
                            <input type="password" id="new_password" name="new_password" class="input-mitra"
                                placeholder="Sandi Baru" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-blue-600 hidden">
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
                                class="input-mitra" placeholder="Ulangi Sandi" required oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleVisibility('new_password_confirmation', this)"
                                class="absolute inset-y-0 right-3 flex.. items-center text-gray-400 hover:text-blue-600 hidden">
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
                            <button type="submit"
                                class="w-full bg-gray-800 text-white py-2.5 rounded-2xl text-xs font-bold hover:bg-black transition active:scale-95">
                                GANTI PASSWORD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .input-mitra {
            width: 100%;
            margin-top: 2px;
            padding: 0.55rem 0.9rem;
            border: 1px solid #f1f5f9;
            background-color: #f8fafc;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .input-mitra:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.05);
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
        // ====================================
        // FORM UPDATE DATA MITRA
        // ====================================
        document.getElementById('formMitra')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'MENYIMPAN...';

            fetch("{{ route('mitra-perusahaan.profil_mitra.update_data') }}", {
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
                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal menyimpan data');
                    }
                    return data;
                })
                .then(data => {
                    alert(data.message || 'Data mitra berhasil diperbarui');
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Terjadi kesalahan saat menyimpan data');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // ====================================
        // FORM UPDATE PASSWORD
        // ====================================
        document.getElementById('formPassword')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('new_password_confirmation');

            // Validasi frontend sederhana
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

            fetch("{{ route('mitra-perusahaan.profil_mitra.update_password') }}", {
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
                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal mengubah password');
                    }
                    return data;
                })
                .then(data => {
                    alert(data.message || 'Password berhasil diubah');
                    form.reset();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Terjadi kesalahan saat mengubah password');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // Memunculkan tombol mata hanya saat ada input
        function handleVisibility(input) {
            const btn = input.parentElement.querySelector('button');
            if (input.value.length > 0) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }

        // Mengganti tipe input antara password dan text
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