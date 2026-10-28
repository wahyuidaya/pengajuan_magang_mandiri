<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StafProdi\ActivityLogController;
class ArsipController extends Controller
{
    /**
     * Tampilkan halaman arsip data magang
     */
    public function index(Request $request)
    {
        $query = PengajuanMagang::with([
                'mahasiswa',
                'mitra',
                'dosenPembimbing',
                'laporanmingguan',
                'laporanAkhir',
                'nilaiAkhir'
            ])
            ->where('status', 'disetujui')
            ->whereHas('nilaiAkhir', function ($q) {
                $q->where(function($q2) {
                    $q2->whereNotNull('nilai_huruf')
                    ->where('nilai_huruf', '<>', '')
                    ->orWhere('nilai_angka', '>', 0);
                });
            });

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($mhs) use ($search) {
                    $mhs->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('npm', 'like', "%{$search}%");
                })
                    ->orWhereHas('mitra', function ($mitra) use ($search) {
                        $mitra->where('nama_perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        $arsipMagang = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(10);

        return view('dashboard.staf-prodi.arsip_data_magang', compact('arsipMagang'));
    }

    /**
     * Get detail arsip untuk modal (AJAX)
     */
    public function detail($id)
    {
        $pengajuan = PengajuanMagang::with([
            'mahasiswa',
            'mitra',
            'dosenPembimbing',
            'laporanmingguan',
            'laporanAkhir',
            'nilaiAkhir'
        ])
            ->findOrFail($id);

        // Ambil nilai akhir
        $nilaiAkhir = NilaiAkhir::where('id_pengajuan', $id)->first();

        // Siapkan data dokumen
        $dokumen = [];

        // KRS
        if ($pengajuan->krs_url) {
            $dokumen[] = [
                'nama' => 'KRS',
                'file_path' => $pengajuan->krs_url,
                'ukuran' => $this->getFileSize($pengajuan->krs_url),
                'url' => asset('storage/' . $pengajuan->krs_url)
            ];
        }

        // Transkrip Nilai
        if ($pengajuan->transkrip_url) {
            $dokumen[] = [
                'nama' => 'Transkrip Nilai',
                'file_path' => $pengajuan->transkrip_url,
                'ukuran' => $this->getFileSize($pengajuan->transkrip_url),
                'url' => asset('storage/' . $pengajuan->transkrip_url)
            ];
        }

        // Surat Pengantar Mahasiswa
        if ($pengajuan->surat_pengantar_mahasiswa) {
            $dokumen[] = [
                'nama' => 'Surat Pengantar',
                'file_path' => $pengajuan->surat_pengantar_mahasiswa,
                'ukuran' => $this->getFileSize($pengajuan->surat_pengantar_mahasiswa),
                'url' => asset('storage/' . $pengajuan->surat_pengantar_mahasiswa)
            ];
        }

        // laporan mingguan (ambil semua)
        foreach ($pengajuan->laporanmingguan as $laporan) {
            if ($laporan->file_path) {
                $dokumen[] = [
                    'nama' => 'laporan mingguan - ' . $laporan->judul_laporan,
                    'file_path' => $laporan->file_path,
                    'ukuran' => $this->getFileSize($laporan->file_path),
                    'url' => asset('storage/' . $laporan->file_path)
                ];
            }
        }

        // Laporan Akhir
        if ($pengajuan->laporanAkhir && $pengajuan->laporanAkhir->file_path) {
            $dokumen[] = [
                'nama' => 'Laporan Akhir',
                'file_path' => $pengajuan->laporanAkhir->file_path,
                'ukuran' => $this->getFileSize($pengajuan->laporanAkhir->file_path),
                'url' => asset('storage/' . $pengajuan->laporanAkhir->file_path)
            ];
        }


        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $pengajuan->mahasiswa->nama_lengkap,
                'npm' => $pengajuan->mahasiswa->npm,
                'perusahaan' => $pengajuan->mitra->nama_perusahaan,
                'tanggal_magang' => $pengajuan->periode_magang ?? '-',
                'dosen_pembimbing' => $pengajuan->dosenPembimbing->nama_dosen ?? '-',
                'nilai_akhir' => $nilaiAkhir->nilai_huruf ?? $pengajuan->nilai_akhir ?? '-',
                'dokumen' => $dokumen
            ]
        ]);
    }

    /**
     * Download dokumen arsip
     */
    public function downloadDokumen($id, $jenis)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);

        $filePath = match ($jenis) {
            'krs' => $pengajuan->krs_url,
            'transkrip' => $pengajuan->transkrip_url,
            'surat_pengantar' => $pengajuan->surat_pengantar_mahasiswa,
            'laporan_akhir' => $pengajuan->laporanAkhir?->file_path,
            default => null
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        ActivityLogController::saveLog(
            'Download Dokumen Arsip', 
            'Dokumen: ' . strtoupper($jenis) . ' - Milik Mahasiswa: ' . $pengajuan->mahasiswa->nama_lengkap
        );

        return response()->download(
            storage_path('app/public/' . $filePath)
        );
    }

    /**
     * Download laporan mingguan
     */
    public function downloadlaporanmingguan($id)
    {
        $laporan = \App\Models\laporanmingguan::findOrFail($id);

        if (!$laporan->file_path || !Storage::disk('public')->exists($laporan->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        ActivityLogController::saveLog(
            'Download Laporan Mingguan', 
            'Judul: ' . $laporan->judul_laporan . ' - Milik Mahasiswa: ' . $laporan->pengajuan->mahasiswa->nama_lengkap
        );

        return response()->download(
            storage_path('app/public/' . $laporan->file_path)
        );

    }

    /**
     * Helper: Get file size
     */
    private function getFileSize($filePath)
    {
        if (!$filePath)
            return '0 KB';

        try {
            if (Storage::disk('public')->exists($filePath)) {
                $bytes = Storage::disk('public')->size($filePath);

                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 0) . ' KB';
                } else {
                    return $bytes . ' B';
                }
            }
        } catch (\Exception $e) {
            return '0 KB';
        }

        return '0 KB';
    }


    /**
     * Export arsip ke Excel (optional)
     */
    public function export(Request $request)
    {
        // TODO: Implementasi export menggunakan Laravel Excel
        // return Excel::download(new ArsipMagangExport($request->all()), 'arsip-magang.xlsx');
    }
}