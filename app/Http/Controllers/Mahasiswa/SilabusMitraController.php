<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SilabusMitra;
use App\Models\PengajuanMagang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SilabusMitraController extends Controller
{
    /**
     * Tampilkan halaman silabus dari mitra
     */
    public function index()
    {
        // Get mahasiswa yang sedang login
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('id_user', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('dashboard')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Ambil semua pengajuan magang yang disetujui milik mahasiswa ini
        $pengajuanList = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
            ->where('status', 'disetujui')
            ->with(['mitra', 'silabusMitra'])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        // Grouping silabus berdasarkan perusahaan
        $silabusByPerusahaan = [];
        
        foreach ($pengajuanList as $pengajuan) {
            if ($pengajuan->silabusMitra && $pengajuan->mitra) {
                $namaPerusahaan = $pengajuan->mitra->nama_perusahaan;
                
                if (!isset($silabusByPerusahaan[$namaPerusahaan])) {
                    $silabusByPerusahaan[$namaPerusahaan] = [];
                }
                
                $silabusByPerusahaan[$namaPerusahaan][] = [
                    'silabus' => $pengajuan->silabusMitra,
                    'pengajuan' => $pengajuan
                ];
            }
        }

        return view(
            'dashboard.mahasiswa.silabus_dari_mitra',
            compact('silabusByPerusahaan')
        );
    }

    /**
     * Download file silabus
     */
    public function download($id_silabus)
    {
        try {
            // Get mahasiswa yang sedang login
            $user = Auth::user();
            $mahasiswa = Mahasiswa::where('id_user', $user->id_user)->first();

            if (!$mahasiswa) {
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
            }

            // Get silabus
            $silabus = SilabusMitra::with(['pengajuan'])->find($id_silabus);

            if (!$silabus) {
                return redirect()->back()->with('error', 'Silabus tidak ditemukan');
            }

            // Validasi: Pastikan silabus ini untuk mahasiswa yang sedang login
            if ($silabus->pengajuan->id_mhs !== $mahasiswa->id_mhs) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke silabus ini');
            }

            // Check if file exists
            if (!$silabus->file_silabus || !Storage::disk('public')->exists($silabus->file_silabus)) {
                return redirect()->back()->with('error', 'File silabus tidak ditemukan');
            }

            // Get file path and name
            $filePath = Storage::disk('public')->path($silabus->file_silabus);
            $fileName = basename($silabus->file_silabus);

            // Download file
            return response()->download($filePath, $fileName);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Get file size helper
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}