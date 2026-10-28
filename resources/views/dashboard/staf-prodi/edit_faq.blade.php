@extends('layouts.app_staf-prodi', ['activeMenu' => 'faq', 'title' => 'Dashboard Staf Prodi'])

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit FAQ</h1>
        <a href="{{ route('staf-prodi.faq') }}">
            <button class="flex items-center space-x-2 px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </button>
        </a>
    </div>

    @if(isset($faq))
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        <form action="{{ route('staf-prodi.faq.update', $faq->id_faq) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Pertanyaan --}}
            <div class="mb-6">
                <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                    Pertanyaan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="pertanyaan" 
                    name="pertanyaan" 
                    placeholder="Contoh: Apa syarat untuk mengajukan magang mandiri?"
                    value="{{ old('pertanyaan', $faq->pertanyaan) }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-purple-500 focus:border-purple-500 {{ $errors->has('pertanyaan') ? 'border-red-500' : 'border-gray-300' }}"
                    required
                    maxlength="200">
                @error('pertanyaan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal 200 karakter</p>
            </div>

            {{-- Jawaban --}}
            <div class="mb-6">
                <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-2">
                    Jawaban <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="jawaban" 
                    name="jawaban"
                    rows="6"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-purple-500 focus:border-purple-500 resize-y {{ $errors->has('jawaban') ? 'border-red-500' : 'border-gray-300' }}"
                    required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                @error('jawaban')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Urutan --}}
            <div class="mb-6">
                <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                    Urutan Tampilan (Opsional)
                </label>
                <input 
                    type="number" 
                    id="urutan" 
                    name="urutan" 
                    placeholder="0"
                    value="{{ old('urutan', $faq->urutan) }}"
                    min="0"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-purple-500 focus:border-purple-500 {{ $errors->has('urutan') ? 'border-red-500' : 'border-gray-300' }}">
                @error('urutan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Urutan kecil = tampil lebih dulu</p>
            </div>

            {{-- Button --}}
            <div class="flex space-x-4">
                <button 
                    type="submit"
                    class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-200 shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update FAQ</span>
                </button>

                <a href="{{ route('staf-prodi.faq') }}"
                    class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
    @else
        <div class="text-center text-red-500 font-semibold p-6">
            ❌ Data FAQ tidak ditemukan.
        </div>
    @endif
</div>
@endsection
