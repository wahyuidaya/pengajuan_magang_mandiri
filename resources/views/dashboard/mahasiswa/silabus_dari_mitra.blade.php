@extends('layouts.app_mahasiswa', ['activeMenu' => 'silabus_dari_mitra', 'title' => 'Silabus dari Mitra'])
@section('content')
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800">Silabus dari Mitra</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat dan unduh silabus yang telah dikirimkan oleh perusahaan mitra magang</p>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @forelse($silabusByPerusahaan as $namaPerusahaan => $silabusList)
            <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-[#690dac] p-4 text-white font-semibold text-lg">
                    {{ $namaPerusahaan }}
                </div>
                <div class="p-4 space-y-3">
                    @foreach($silabusList as $item)
                        @php
                            $silabus = $item['silabus'];
                            $pengajuan = $item['pengajuan'];
                            
                            // Get file info
                            $fileName = $silabus->file_silabus ? basename($silabus->file_silabus) : 'Silabus.pdf';
                            $fileSize = '0 KB';
                            $tanggalUpload = $silabus->tanggal_upload ? date('Y-m-d', strtotime($silabus->tanggal_upload)) : date('Y-m-d');
                            
                            // Get actual file size if exists
                            if ($silabus->file_silabus && Storage::disk('public')->exists($silabus->file_silabus)) {
                                $bytes = Storage::disk('public')->size($silabus->file_silabus);
                                if ($bytes >= 1048576) {
                                    $fileSize = number_format($bytes / 1048576, 1) . ' MB';
                                } elseif ($bytes >= 1024) {
                                    $fileSize = number_format($bytes / 1024, 1) . ' KB';
                                } else {
                                    $fileSize = $bytes . ' B';
                                }
                            }
                        @endphp
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-[#690dac]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9 13H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9 17H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="9" cy="13" r="1.5" fill="white" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $fileName }}</div>
                                    <div class="flex items-center text-xs text-gray-500 mt-0.5 space-x-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3M16 7V3M6 11H18M7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V5C19 4.46957 18.7893 3.96086 18.4142 3.58579C18.0391 3.21071 17.5304 3 17 3H7C6.46957 3 5.96086 3.21071 5.58579 3.58579C5.21071 3.96086 5 4.46957 5 5V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21Z">
                                            </path>
                                        </svg>
                                        <span>{{ $tanggalUpload }}</span>
                                        <span class="text-sm text-gray-400">•</span>
                                        <span>{{ $fileSize }}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('mahasiswa.silabus_dari_mitra.download', $silabus->id_silabus) }}" 
                               class="text-gray-400 hover:text-[#690dac] transition duration-150 p-2 rounded-full">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 3V17M12 17L17 12M12 17L7 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M20 21H4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Silabus</h3>
                <p class="mt-2 text-sm text-gray-500">Silabus dari mitra akan muncul di sini setelah mereka mengirimkannya</p>
            </div>
        @endforelse
    </div>
@endsection