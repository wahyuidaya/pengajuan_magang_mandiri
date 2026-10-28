@extends('layouts.app_staf-prodi', ['activeMenu' => 'faq', 'title' => 'Dashboard Staf Prodi'])
@section('content')
    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manajemen FAQ</h1>
            <a href="{{ route('staf-prodi.tambah_faq') }}">
                <button
                    class="flex items-center space-x-2 px-4 py-2 bg-[#690dac] text-white font-semibold rounded-lg hover:bg-[#570992] transition duration-200 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah FAQ</span>
                </button>
            </a>
        </div>

        <div class="space-y-4">
            @forelse($faqList as $faq)
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200" id="faq-{{ $faq->id_faq }}">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $faq->pertanyaan }}</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('staf-prodi.faq.edit', $faq->id_faq) }}" title="Edit FAQ" class="text-blue-500 hover:text-blue-700">
                            <a href="{{ route('staf-prodi.faq.edit', $faq->id_faq) }}" title="Edit FAQ" class="text-blue-500 hover:text-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                        </a>
                        <form action="{{ route('staf-prodi.faq.delete', $faq->id_faq) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus FAQ" class="text-red-500 hover:text-red-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-gray-600 mt-2">{!! nl2br(e($faq->jawaban)) !!}</p>
            </div>
            @empty
            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Belum ada FAQ yang ditambahkan</p>
                <p class="text-gray-400 text-sm mt-2">Klik tombol "Tambah FAQ" untuk membuat FAQ baru</p>
            </div>
            @endforelse
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    @if (session('success'))
        alert(@json(session('success')));
    @endif

    @if (session('error'))
        alert(@json(session('error')));
    @endif
});
    </script>
@endsection