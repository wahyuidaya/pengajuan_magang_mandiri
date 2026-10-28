@extends('layouts.app_mahasiswa', ['activeMenu' => 'profil_mahasiswa', 'title' => 'Profil Saya'])

@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Header Ultra Compact --}}
        <div class="flex justify-between items-end mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 leading-none">Profil Saya</h1>
                <p class="text-xs text-gray-500 mt-1">Informasi akun dan keamanan.</p>
            </div>
            <div id="alertContainer" class="fixed top-20 right-10 z-50"></div>
        </div>

        {{-- Layout Utama --}}
        <div class="grid grid-cols-12 gap-4 flex-1 min-h-0">

            {{-- KIRI: FOTO & PASSWORD (GABUNG JADI SATU KOLOM) --}}
            <div class="col-span-4 flex flex-col gap-4 min-h-0">
                {{-- Kartu Foto --}}
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col items-center justify-center">
                    <div class="relative inline-block mb-3">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-violet-100 shadow-sm mx-auto">
                            @if ($mahasiswa->foto_url)
                                <img id="fotoPreview" src="{{ Storage::url($mahasiswa->foto_url) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div id="fotoPreview"
                                    class="w-full h-full bg-gradient-to-br.. from-violet-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                                    {{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <button onclick="document.getElementById('fotoInput').click()"
                            class="absolute bottom-0 right-0 bg-[#690dac] text-white p-1.5 rounded-full shadow-lg border-2 border-white hover:scale-105 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </button>
                        <input type="file" id="fotoInput" class="hidden" accept="image/*" onchange="uploadFoto()">
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 truncate w-full text-center">{{ $mahasiswa->nama_lengkap }}
                    </h3>
                    <p class="text-xs font-semibold text-violet-600">{{ $mahasiswa->npm }}</p>
                    @if ($mahasiswa->foto_url)
                        <button onclick="hapusFoto()" class="mt-1 text-[10px] text-red-500 hover:underline italic">Hapus
                            Foto</button>
                    @endif
                </div>

                {{-- Kartu Password - Dibuat lebih tipis --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex-1 overflow-hidden">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 border-b pb-1">Ubah
                        Password</h2>
                    <form id="formPassword" class="space-y-2">
                        @csrf @method('PUT')

                        {{-- Sandi Lama --}}
                        <div class="input-password-wrapper">
                            <input type="password" id="current_password" name="current_password" class="input-ultra-compact"
                                placeholder="Sandi Lama" oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleProfilePassword('current_password', this)"
                                class="absolute inset-y-0 right-2 flex.. items-center text-gray-400 hover:text-[#690dac] hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/center" fill="none"
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
                        <div class="input-password-wrapper">
                            <input type="password" id="new_password" name="new_password" class="input-ultra-compact"
                                placeholder="Sandi Baru" oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleProfilePassword('new_password', this)"
                                class="absolute inset-y-0 right-2 flex.. items-center text-gray-400 hover:text-[#690dac] hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/center" fill="none"
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

                        {{-- Konfirmasi Sandi Baru --}}
                        <div class="input-password-wrapper">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="input-ultra-compact" placeholder="Konfirmasi Baru" oninput="handleVisibility(this)">
                            <button type="button" onclick="toggleProfilePassword('new_password_confirmation', this)"
                                class="absolute inset-y-0 right-2 flex.. items-center text-gray-400 hover:text-[#690dac] hidden">
                                <svg class="eye-open h-4 w-4" xmlns="http://www.w3.org/2000/center" fill="none"
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

                        <button type="submit"
                            class="w-full bg-gray-800 text-white py-2 rounded-lg text-xs font-bold hover:bg-black transition">Update
                            Password</button>
                    </form>
                </div>
            </div>

            {{-- KANAN: FORM DATA PRIBADI --}}
            <div class="col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 h-full flex flex-col">
                    <h2 class="text-[11px] font-bold text-[#690dac] uppercase tracking-widest mb-4 border-b pb-1">Data
                        Mahasiswa</h2>

                    <form id="formProfil" class="grid grid-cols-2 gap-x-4 gap-y-3 flex-1">
                        @csrf @method('PUT')

                        <div class="col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ $mahasiswa->nama_lengkap }}"
                                class="input-ultra-compact bg-gray-50 text-gray-400" readonly>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">NPM</label>
                            <input type="text" name="npm" value="{{ $mahasiswa->npm }}"
                                class="input-ultra-compact bg-gray-50 text-gray-400" readonly>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Kelas</label>
                            <input type="text" name="kelas" value="{{ $mahasiswa->kelas }}"
                                class="input-ultra-compact bg-gray-50 text-gray-400" readonly>
                        </div>

                        <div class="">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Program Studi</label>
                            <input type="text" name="prodi" value="{{ $mahasiswa->prodi }}"
                                class="input-ultra-compact bg-gray-50 text-gray-400" readonly>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">IPK</label>
                            <input type="number" step="0.01" name="ipk" value="{{ $mahasiswa->ipk }}"
                                class="input-ultra-compact bg-gray-50 text-gray-400" readonly>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Email</label>
                            <input type="email" name="email" value="{{ $mahasiswa->email }}" class="input-ultra-compact"
                                required>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">No HP</label>
                            <input type="text" name="no_hp" value="{{ $mahasiswa->no_hp }}" class="input-ultra-compact"
                                required>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="col-span-2 flex items-end pt-2">
                            <button type="submit"
                                class="w-full bg-[#690dac] text-white py-3 rounded-xl text-sm font-bold hover:bg-violet-800 transition-all shadow-md active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styling agar input dan icon menyatu */
        .input-password-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 0.5rem;
            /* Memberi jarak antar input */
        }

        .input-password-wrapper .input-ultra-compact {
            padding-right: 2.5rem !important;
            /* Memberi ruang agar teks tidak tertutup icon */
            margin-top: 0;
        }

        /* Sembunyikan icon mata bawaan Edge/IE agar tidak double */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }

        /* Styling input ultra compact yang sudah ada */
        .input-ultra-compact {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .input-ultra-compact:focus {
            outline: none;
            border-color: #690dac;
            box-shadow: 0 0 0 2px rgba(105, 13, 172, 0.1);
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
        // Upload Foto
        function uploadFoto() {
            const fileInput = document.getElementById('fotoInput');
            const file = fileInput.files[0];
            if (!file) return;

            if (file.size > 2048 * 1024) {
                alert('Ukuran file maksimal 2MB');
                return;
            }

            const formData = new FormData();
            formData.append('foto', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('mahasiswa.profil_mahasiswa.foto.upload') }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        const preview = document.getElementById('fotoPreview');
                        if (preview.tagName === 'IMG') {
                            preview.src = data.foto_url;
                        } else {
                            preview.outerHTML = `<img id="fotoPreview" src="${data.foto_url}" class="w-full h-full object-cover">`;
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Terjadi kesalahan saat mengupload foto'));
        }

        // Hapus Foto
        function hapusFoto() {
            if (!confirm('Apakah Anda yakin ingin menghapus foto profil?')) return;

            fetch('{{ route('mahasiswa.profil_mahasiswa.foto.delete') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) window.location.reload();
                })
                .catch(() => alert('Terjadi kesalahan saat menghapus foto'));
        }

        // Update Profil
        document.getElementById('formProfil').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            fetch('{{ route('mahasiswa.profil_mahasiswa.update') }}', {
                method: 'POST',
                headers: { 'X-HTTP-Method-Override': 'PUT' },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) setTimeout(() => window.location.reload(), 1000);
                })
                .catch(() => alert('Terjadi kesalahan saat menyimpan data'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // Update Password
        document.getElementById('formPassword').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            if (formData.get('new_password') !== formData.get('new_password_confirmation')) {
                alert('Konfirmasi password tidak cocok');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengubah...';

            fetch('{{ route('mahasiswa.profil_mahasiswa.password.update') }}', {
                method: 'POST',
                headers: { 'X-HTTP-Method-Override': 'PUT' },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) this.reset();
                })
                .catch(() => alert('Terjadi kesalahan saat mengubah password'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

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

@endsection