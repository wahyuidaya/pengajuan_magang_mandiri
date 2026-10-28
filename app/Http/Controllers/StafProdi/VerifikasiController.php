<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StafProdi\ActivityLogController;

class VerifikasiController extends Controller
{
    /**
     * Tampilkan halaman verifikasi pengajuan
     */
public function index(Request $request)
{
    try {
        // 1. Mulai query dasar (Sembunyikan non_aktif)
        $query = PengajuanMagang::with('mahasiswa')
                    ->where('status', '!=', 'non_aktif');

        // 2. Filter Status (Pastikan value di dropdown HTML adalah: diajukan, disetujui, atau ditolak)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('npm', 'like', "%{$search}%");
                })
                ->orWhere('nama_perusahaan', 'like', "%{$search}%"); 
            });
        }

        // 4. Sorting (DIPERBAIKI: Menggunakan 'diajukan' sesuai dengan value di HTML Anda)
        $query->orderByRaw("CASE WHEN status = 'diajukan' THEN 0 ELSE 1 END ASC")
              ->orderBy('created_at', 'desc');

        // 5. Eksekusi
        $pengajuanList = $query->paginate(10)->appends($request->query());

        return view('dashboard.staf-prodi.verifikasi_pengajuan', compact('pengajuanList'));

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_verifikator' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = PengajuanMagang::findOrFail($id);
            $statusLama = $pengajuan->status;

            // APPROVE TANPA MATA KULIAH
            $pengajuan->update([
                'status' => 'disetujui',
                'catatan_verifikator' => $validated['catatan_verifikator'] ?? 'Pengajuan disetujui.',
            ]);

            $this->logStatusChange(
                $pengajuan,
                $statusLama,
                'disetujui',
                $validated['catatan_verifikator'] ?? 'Pengajuan disetujui'
            );

            DB::commit();

            ActivityLogController::saveLog(
                'Verifikasi Pengajuan', 
                "MENYETUJUI pengajuan magang mahasiswa: {$pengajuan->mahasiswa->nama_lengkap} ({$pengajuan->mahasiswa->npm})"
            );

            return redirect()->route('staf-prodi.verifikasi_pengajuan')
                ->with('success', 'Pengajuan berhasil disetujui.')
                ->with('notif_tentukan_dospem', true)
                ->with('notif_url', route('staf-prodi.staf_prodi_data_mahasiswa'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan mahasiswa
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_verifikator' => 'required|string|max:500',
        ], [
            'catatan_verifikator.required' => 'Alasan penolakan wajib diisi',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = PengajuanMagang::findOrFail($id);
            $statusLama = $pengajuan->status;

            // Update status menjadi ditolak
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_verifikator' => $validated['catatan_verifikator'],
            ]);

            // Log status change
            $this->logStatusChange($pengajuan, $statusLama, 'ditolak', $validated['catatan_verifikator']);

            DB::commit();

            ActivityLogController::saveLog(
                'Verifikasi Pengajuan', 
                "MENOLAK pengajuan magang mahasiswa: {$pengajuan->mahasiswa->nama_lengkap}. Alasan: {$validated['catatan_verifikator']}"
            );

            return redirect()->route('staf-prodi.verifikasi_pengajuan')
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetPengajuan($id)
        {
            try {
                $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);
                
                $namaMhs = $pengajuan->mahasiswa->nama_lengkap ?? 'Unknown';
                $npmMhs = $pengajuan->mahasiswa->npm ?? 'Unknown';

                $pengajuan->delete();

                ActivityLogController::saveLog(
                    'Verifikasi Pengajuan', 
                    "Mereset/Menghapus permanen pengajuan magang mahasiswa: $namaMhs ($npmMhs). Mahasiswa diizinkan daftar ulang."
                );

                return back()->with('success', 'Data pengajuan lama berhasil dihapus. Mahasiswa sekarang dapat mengajukan kembali dari awal.');

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses penghapusan data: ' . $e->getMessage());
            }
        }

    /**
     * Minta revisi dari mahasiswa
     */
    public function revision(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_verifikator' => 'required|string|max:500',
        ], [
            'catatan_verifikator.required' => 'Catatan revisi wajib diisi',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = PengajuanMagang::findOrFail($id);
            $statusLama = $pengajuan->status;

            // Update status menjadi revisi
            $pengajuan->update([
                'status' => 'revisi',
                'catatan_verifikator' => $validated['catatan_verifikator'],
            ]);

            // Log status change
            $this->logStatusChange($pengajuan, $statusLama, 'revisi', $validated['catatan_verifikator']);

            DB::commit();

            ActivityLogController::saveLog(
                'Verifikasi Pengajuan', 
                "MEMINTA REVISI pengajuan magang mahasiswa: {$pengajuan->mahasiswa->nama_lengkap}"
            );

            return redirect()->route('staf-prodi.verifikasi_pengajuan')
                ->with('success', 'Pengajuan dikembalikan untuk revisi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Lihat detail pengajuan
     */
    public function show($id)
    {
        try {

            return view('dashboard.staf-prodi.detail_pengajuan', compact('pengajuan'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Pengajuan tidak ditemukan.');
        }
    }

    /**
     * View dokumen pengajuan di browser (tanpa download)
     */
    public function viewDokumen($id, $jenis)
    {
        try {
            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);

            // Tentukan file path berdasarkan jenis dokumen
            $filePath = null;

            switch ($jenis) {
                case 'krs':
                    $filePath = $pengajuan->krs_url;
                    break;
                case 'transkrip':
                    $filePath = $pengajuan->transkrip_url;
                    break;
                case 'surat':
                    $filePath = $pengajuan->surat_pengantar_mahasiswa;
                    break;
                default:
                    return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
            }

            // Cek apakah file exists
            if (!$filePath || !Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            // Get full path
            $fullPath = Storage::disk('public')->path($filePath);

            // Return file untuk ditampilkan di browser
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuka dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen pengajuan
     */
    public function downloadDokumen($id, $jenis)
    {
        try {
            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id);

            // Tentukan file path berdasarkan jenis dokumen
            $filePath = null;
            $fileName = null;

            switch ($jenis) {
                case 'krs':
                    $filePath = $pengajuan->krs_url;
                    $fileName = 'KRS_' . $pengajuan->mahasiswa->npm . '.pdf';
                    break;
                case 'transkrip':
                    $filePath = $pengajuan->transkrip_url;
                    $fileName = 'Transkrip_' . $pengajuan->mahasiswa->npm . '.pdf';
                    break;
                case 'surat':
                    $filePath = $pengajuan->surat_pengantar_mahasiswa;
                    $fileName = 'SuratPengantar_' . $pengajuan->mahasiswa->npm . '.pdf';
                    break;
                default:
                    return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
            }

            // Cek apakah file exists
            if (!$filePath || !Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            // Get full path dan download file
            $fullPath = Storage::disk('public')->path($filePath);

            return response()->download($fullPath, $fileName);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Log perubahan status
     */
    private function logStatusChange($pengajuan, $statusDari, $statusKe, $catatan)
    {
        try {
            \App\Models\LogStatusPengajuan::create([
                'id_log' => (string) \Illuminate\Support\Str::uuid(),
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_dari' => $statusDari,
                'status_ke' => $statusKe,
                'pelaku' => Auth::id(),
                'catatan' => $catatan,
                'timestamp' => now(),
            ]);
        } catch (\Exception $e) {
            // Silent fail untuk log
            \Log::error('Failed to log status change: ' . $e->getMessage());
        }
    }
}