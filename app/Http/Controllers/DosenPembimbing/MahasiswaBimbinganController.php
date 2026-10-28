<?php

namespace App\Http\Controllers\DosenPembimbing;

use App\Models\PengajuanMagang;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class MahasiswaBimbinganController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa bimbingan
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = DosenPembimbing::where('id_user', $user->id_user)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $search = $request->query('search');

        $mahasiswaBimbingan = PengajuanMagang::with([
                'mahasiswa',
                'mitra',
                'laporanmingguan',
                'laporanAkhir'
            ])
            ->where('id_dosen', $dosen->id_dosen)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->when($search, function ($query, $search) {
                $query->whereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('npm', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view(
            'dashboard.dosen-pembimbing.daftar_mahasiswa_bimbingan',
            compact('mahasiswaBimbingan')
        );
    }

    /**
     * Menampilkan detail mahasiswa bimbingan (AJAX)
     */
    public function detailMahasiswaBimbingan($id_pengajuan)
    {
        $pengajuan = PengajuanMagang::with([
            'mahasiswa',
            'mitra',
            'laporanmingguan' => function ($query) {
                $query->orderBy('minggu_ke', 'asc');
            },
            'laporanAkhir'
        ])->findOrFail($id_pengajuan);

        $files = [];

        // File dokumen pengajuan
        $this->addFileIfExists($files, 'KRS', $pengajuan->krs_url);
        $this->addFileIfExists($files, 'Transkrip Nilai', $pengajuan->transkrip_url);
        $this->addFileIfExists($files, 'Surat Pengantar Mahasiswa', $pengajuan->surat_pengantar_mahasiswa);

        // laporan mingguan
        foreach ($pengajuan->laporanmingguan as $laporan) {
            if ($laporan->file_path) {
                $files[] = [
                    'name' => 'Laporan Minggu ke-' . $laporan->minggu_ke,
                    'type' => 'PDF',
                    'url' => asset('storage/' . $laporan->file_path),
                    'size' => $this->formatFileSize($laporan->file_path),
                    'status' => $laporan->status,
                    'id' => $laporan->id_laporan_mingguan,
                    'jenis' => 'mingguan',
                    'komentar_pembimbing' => $laporan->catatan_reviewer,
                ];
            }
        }

        // Laporan akhir
        if ($pengajuan->laporanAkhir && $pengajuan->laporanAkhir->file_path) {
            $files[] = [
                'name' => 'Laporan Akhir',
                'type' => 'PDF',
                'url' => asset('storage/' . $pengajuan->laporanAkhir->file_path),
                'size' => $this->formatFileSize($pengajuan->laporanAkhir->file_path),
                'status' => $pengajuan->laporanAkhir->status,
                'id' => $pengajuan->laporanAkhir->id_laporan_akhir,
                'jenis' => 'akhir',
                'komentar_pembimbing' => $pengajuan->laporanAkhir->catatan_reviewer,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $pengajuan->mahasiswa->nama_lengkap,
                'npm' => $pengajuan->mahasiswa->npm,
                'perusahaan' => $pengajuan->mitra->nama_perusahaan ?? '-',
                'status' => $this->getStatusLabel($pengajuan->status),
                'files' => $files,
            ]
        ]);
    }

    /**
     * Helper: Tambahkan file ke array jika ada
     */
    private function addFileIfExists(&$files, $name, $filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $files[] = [
                'name' => $name,
                'type' => 'PDF',
                'url' => asset('storage/' . $filePath),
                'size' => $this->formatFileSize($filePath)
            ];
        }
    }

    /**
     * Helper: Format ukuran file
     */
    private function formatFileSize($filePath)
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($filePath)) {
            return '0 KB';
        }

        $bytes = $disk->size($filePath);
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Helper: Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'disetujui' => 'Aktif',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai'
        ];

        return $labels[$status] ?? $status;
    }
}