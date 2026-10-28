{{-- Modal Approve dengan Input Mata Kuliah Eligible --}}
<div id="approveModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" 
    style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto animate-fade-in">
        <div class="sticky top-0 z-10 bg-green-600 text-white px-5 py-4 rounded-t-xl flex items-center justify-between">
            <h2 class="text-lg font-bold">Setujui Pengajuan & Tentukan Mata Kuliah Eligible</h2>
            <button onclick="closeApproveModal()" class="text-white hover:text-gray-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="approveForm" method="POST" class="p-6">
            @csrf
            
            {{-- Info Alert --}}
            <div class="mb-5 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Penting:</strong> Tentukan mata kuliah yang dapat dikonversi berdasarkan KRS mahasiswa dan silabus dari mitra. 
                            Mahasiswa nantinya hanya bisa memilih dari mata kuliah yang Anda tentukan di sini.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Section: Pilih Mata Kuliah Eligible --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Pilih Mata Kuliah yang Dapat Dikonversi
                </h3>
                
                {{-- Search Box --}}
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="searchMatkul" placeholder="Cari mata kuliah..." 
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Daftar Mata Kuliah --}}
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto">
                    <div id="matkulList" class="space-y-2">
                        @php
                            // Master Data Mata Kuliah - Sesuaikan dengan database Anda
                            $masterMatkul = [
                                ['kode' => 'IF601', 'nama' => 'Praktik Kerja Lapangan', 'sks' => 4],
                                ['kode' => 'IF602', 'nama' => 'Magang Industri', 'sks' => 6],
                                ['kode' => 'IF501', 'nama' => 'Proyek Pengembangan Sistem', 'sks' => 4],
                                ['kode' => 'IF502', 'nama' => 'Pengembangan Aplikasi Web', 'sks' => 3],
                                ['kode' => 'IF503', 'nama' => 'Manajemen Proyek TI', 'sks' => 3],
                                ['kode' => 'IF504', 'nama' => 'Basis Data Lanjut', 'sks' => 3],
                                ['kode' => 'IF505', 'nama' => 'Pemrograman Mobile', 'sks' => 3],
                                ['kode' => 'IF506', 'nama' => 'Keamanan Informasi', 'sks' => 3],
                                ['kode' => 'IF507', 'nama' => 'Machine Learning', 'sks' => 3],
                                ['kode' => 'IF508', 'nama' => 'Cloud Computing', 'sks' => 3],
                            ];
                        @endphp
                        
                        @foreach($masterMatkul as $mk)
                        <div class="matkul-item flex items-start bg-white rounded-lg p-3 hover:bg-green-50 transition border border-gray-200"
                            data-nama="{{ strtolower($mk['nama']) }}" data-kode="{{ strtolower($mk['kode']) }}">
                            <input type="checkbox" 
                                id="matkul_{{ $mk['kode'] }}" 
                                name="mata_kuliah_eligible[]" 
                                value="{{ json_encode($mk) }}"
                                data-sks="{{ $mk['sks'] }}"
                                class="matkul-checkbox mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="matkul_{{ $mk['kode'] }}" class="ml-3 flex-1 cursor-pointer">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $mk['nama'] }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Kode: {{ $mk['kode'] }} | {{ $mk['sks'] }} SKS
                                </div>
                            </label>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                {{ $mk['sks'] }} SKS
                            </span>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Empty State --}}
                    <div id="emptyState" class="hidden text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Tidak ada mata kuliah yang cocok</p>
                    </div>
                </div>

                {{-- Total SKS Selected --}}
                <div class="mt-4 flex justify-between items-center px-4 py-3 bg-green-50 rounded-lg border border-green-200">
                    <span class="text-sm font-medium text-gray-700">Total SKS yang Dapat Dikonversi:</span>
                    <span id="totalSksEligible" class="text-xl font-bold text-green-600">0 SKS</span>
                </div>
            </div>

            {{-- Catatan untuk Mahasiswa --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan untuk Mahasiswa (Opsional)
                </label>
                <textarea name="catatan_verifikator" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 resize-none"
                    placeholder="Tambahkan catatan atau instruksi untuk mahasiswa..."></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 border-t border-gray-200 pt-4">
                <button type="button" onclick="closeApproveModal()" 
                    class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </button>
                <button type="submit" id="btnSubmitApprove" disabled
                    class="flex-1 px-4 py-2.5 bg-gray-400 text-white rounded-lg cursor-not-allowed transition font-semibold">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Setujui & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}
</style>

<script>
let totalSksEligible = 0;

// Search functionality
document.getElementById('searchMatkul').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const matkulItems = document.querySelectorAll('.matkul-item');
    let visibleCount = 0;
    
    matkulItems.forEach(item => {
        const nama = item.dataset.nama;
        const kode = item.dataset.kode;
        
        if (nama.includes(searchTerm) || kode.includes(searchTerm)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show empty state if no results
    document.getElementById('emptyState').style.display = visibleCount === 0 ? 'block' : 'none';
});

// Handle checkbox changes
document.querySelectorAll('.matkul-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const sks = parseInt(this.getAttribute('data-sks'));
        
        if (this.checked) {
            totalSksEligible += sks;
        } else {
            totalSksEligible -= sks;
        }
        
        updateTotalSksDisplay();
        updateSubmitButton();
    });
});

function updateTotalSksDisplay() {
    document.getElementById('totalSksEligible').textContent = totalSksEligible + ' SKS';
}

function updateSubmitButton() {
    const btn = document.getElementById('btnSubmitApprove');
    const selectedCount = document.querySelectorAll('.matkul-checkbox:checked').length;
    
    if (selectedCount > 0) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        btn.classList.remove('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
    }
}

// Modal functions
function openApproveModal(id) {
    document.getElementById('approveForm').action = `{{ url('verifikasi_pengajuan') }}/${id}/approve`;
    document.getElementById('approveModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Reset state
    totalSksEligible = 0;
    updateTotalSksDisplay();
    updateSubmitButton();
}

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('approveForm').reset();
    document.getElementById('searchMatkul').value = '';
    
    // Reset all checkboxes
    document.querySelectorAll('.matkul-checkbox').forEach(cb => cb.checked = false);
    
    // Show all items
    document.querySelectorAll('.matkul-item').forEach(item => item.style.display = 'flex');
    document.getElementById('emptyState').style.display = 'none';
    
    totalSksEligible = 0;
    updateTotalSksDisplay();
    updateSubmitButton();
}

// Close modal on outside click
document.getElementById('approveModal').addEventListener('click', function(e) {
    if (e.target === this) closeApproveModal();
});

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal();
    }
});
</script>