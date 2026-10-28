<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class CetakSuratController extends Controller
{
    /**
     * Tampilkan halaman cetak surat pengantar
     */
    public function index()
    {
        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();

            if (!$mahasiswa) {
                return view('dashboard.mahasiswa.cetak_surat_pengantar', [
                    'pengajuan' => null,
                    'mahasiswa' => null
                ]);
            }

            // Ambil pengajuan yang sudah disetujui (prioritas terbaru)
            $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->with(['mitra', 'dosenPembimbing'])
                ->latest('tanggal_pengajuan')
                ->first();

            return view('dashboard.mahasiswa.cetak_surat_pengantar', compact('pengajuan', 'mahasiswa'));

        } catch (\Exception $e) {
            return view('dashboard.mahasiswa.cetak_surat_pengantar', [
                'pengajuan' => null,
                'mahasiswa' => null,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Download surat pengantar mahasiswa (yang diupload sendiri)
     */
    public function downloadMahasiswa($id_pengajuan)
    {
        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();
            
            if (!$mahasiswa) {
                return back()->with('error', 'Data mahasiswa tidak ditemukan');
            }

            $pengajuan = PengajuanMagang::where('id_pengajuan', $id_pengajuan)
                ->where('id_mhs', $mahasiswa->id_mhs)
                ->first();

            if (!$pengajuan) {
                return back()->with('error', 'Pengajuan tidak ditemukan');
            }

            if (empty($pengajuan->surat_pengantar_mahasiswa)) {
                return back()->with('error', 'Surat pengantar belum diupload');
            }

            $filePath = storage_path('app/public/' . $pengajuan->surat_pengantar_mahasiswa);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $npm = $mahasiswa->npm ?? 'unknown';
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = "Surat_Pengantar_Mahasiswa_{$npm}.{$extension}";

            return response()->download($filePath, $filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download surat pengantar resmi dari prodi
     */
    public function downloadProdi($id_pengajuan)
    {
        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();
            
            if (!$mahasiswa) {
                return back()->with('error', 'Data mahasiswa tidak ditemukan');
            }

            $pengajuan = PengajuanMagang::where('id_pengajuan', $id_pengajuan)
                ->where('id_mhs', $mahasiswa->id_mhs)
                ->first();

            if (!$pengajuan) {
                return back()->with('error', 'Pengajuan tidak ditemukan');
            }

            if (empty($pengajuan->surat_pengantar_prodi)) {
                return back()->with('error', 'Surat resmi dari prodi belum tersedia. Mohon hubungi Staf Prodi.');
            }

            $filePath = storage_path('app/public/' . $pengajuan->surat_pengantar_prodi);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $npm = $mahasiswa->npm ?? 'unknown';
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = "Surat_Pengantar_Resmi_{$npm}.{$extension}";

            return response()->download($filePath, $filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}