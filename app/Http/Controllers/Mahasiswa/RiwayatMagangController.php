<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PenilaianMagang;

class RiwayatMagangController extends Controller
{
    /**
     * Tampilkan halaman riwayat magang mahasiswa
     */
    public function index()
    {
        // Ambil mahasiswa yang login
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Ambil semua pengajuan magang mahasiswa yang sudah ada nilai akhir
        $riwayatMagang = PengajuanMagang::with([
            'mitra',
            'dosenPembimbing',
            'nilaiAkhir'
        ])
            ->where('id_mhs', $mahasiswa->id_mhs)
            ->whereHas('nilaiAkhir', function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('nilai_huruf')->where('nilai_huruf', '<>', '')
                        ->orWhere(function ($q3) {
                            $q3->whereNotNull('nilai_angka')->where('nilai_angka', '>', 0);
                        });
                });
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get()
            ->map(function ($magang) {
                $nilaiAkhir = $magang->nilaiAkhir->nilai_huruf
                    ?? $magang->nilaiAkhir->nilai_angka
                    ?? '-';

                return [
                    'id_pengajuan' => $magang->id_pengajuan,
                    'nama_perusahaan' => $magang->mitra->nama_perusahaan ?? '-',
                    'posisi' => $magang->posisi_dilamar ?? '-',
                    'periode' => $this->formatPeriode($magang),
                    'status' => 'Selesai',
                    'nilai_akhir' => $nilaiAkhir,
                    'dosen_pembimbing' => $magang->dosenPembimbing->nama_dosen ?? '-',
                ];
            });

        return view('dashboard.mahasiswa.riwayat_magang', compact('riwayatMagang'));
    }

    /**
     * Download file penilaian dalam format PDF
     */


    public function downloadFilePenilaian($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $penilaian = PenilaianMagang::where('id_pengajuan', $id)->first();

        if (!$penilaian || !$penilaian->file_penilaian) {
            return redirect()->back()->with('error', 'File penilaian belum tersedia');
        }

        $filePath = $penilaian->file_penilaian;

        // Gunakan disk 'public'
        if (!\Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        // Download file
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($filePath, basename($filePath));
    }


    /**
     * View detail riwayat magang (optional)
     */
    public function detail($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $pengajuan = PengajuanMagang::with([
            'mahasiswa',
            'mitra',
            'dosenPembimbing',
            'nilaiAkhir',
            'laporanmingguan',
            'laporanAkhir'
        ])
            ->where('id_pengajuan', $id)
            ->where('id_mhs', $mahasiswa->id_mhs)
            ->where('status', 'selesai')
            ->firstOrFail();

        return view('dashboard.mahasiswa.detail_riwayat', compact('pengajuan'));
    }

    /**
     * Helper: Format periode magang
     */
    private function formatPeriode($pengajuan)
    {
        // Cek apakah ada tanggal_mulai dan tanggal_selesai
        if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
            return $pengajuan->tanggal_mulai->format('Y-m-d') . ' s/d ' .
                $pengajuan->tanggal_selesai->format('Y-m-d');
        }

        // Cek dari nilai akhir (jika ada periode di sana)
        if ($pengajuan->nilaiAkhir && $pengajuan->nilaiAkhir->periode) {
            return $pengajuan->nilaiAkhir->periode;
        }

        // Fallback: estimasi dari tanggal pengajuan (3 bulan)
        if ($pengajuan->tanggal_pengajuan) {
            $mulai = $pengajuan->tanggal_pengajuan;
            $selesai = $pengajuan->tanggal_pengajuan->copy()->addMonths(3);
            return $mulai->format('Y-m-d') . ' s/d ' . $selesai->format('Y-m-d');
        }

        return '-';
    }

    /**
     * Export riwayat ke Excel (optional)
     */
    public function exportExcel()
    {
        // TODO: Implementasi export Excel
        // return Excel::download(new RiwayatMagangExport(Auth::user()->mahasiswa->id_mhs), 'riwayat-magang.xlsx');
    }
}