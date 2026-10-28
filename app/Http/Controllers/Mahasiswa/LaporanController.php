<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LaporanMingguan;
use App\Models\LaporanAkhir;
use App\Models\NilaiAkhir;
use App\Models\PengajuanMagang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // ========================================
    // HALAMAN INDEX
    // ========================================
    public function index()
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();

        // Mengambil pengajuan terakhir (termasuk yang non_aktif agar info sanksi muncul)
        $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
            ->whereIn('status', ['disetujui', 'diajukan', 'non_aktif'])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->first();

        // LOGIKA PENGECEKAN SANKSI OTOMATIS (4 MINGGU BOLONG)
        if ($pengajuan && $pengajuan->status === 'disetujui') {
            $laporanUploaded = LaporanMingguan::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->pluck('minggu_ke')
                ->toArray();

            if (count($laporanUploaded) > 0) {
                $mingguAwal = min($laporanUploaded);
                $mingguAkhir = max($laporanUploaded);
                $totalTidakUpload = 0;

                for ($i = $mingguAwal; $i <= $mingguAkhir; $i++) {
                    if (!in_array($i, $laporanUploaded)) {
                        $totalTidakUpload++;
                    }
                }

                // EKSEKUSI SANKSI JIKA BOLONG >= 4
                if ($totalTidakUpload >= 4) {
                    // 1. Hapus File Fisik
                    $allLaporan = LaporanMingguan::where('id_pengajuan', $pengajuan->id_pengajuan)->get();
                    foreach ($allLaporan as $lp) {
                        Storage::disk('public')->delete($lp->file_path);
                    }

                    $laporanAkhirData = LaporanAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->first();
                    if ($laporanAkhirData) {
                        Storage::disk('public')->delete($laporanAkhirData->file_path);
                    }

                    // 2. Wipe Out Database
                    LaporanMingguan::where('id_pengajuan', $pengajuan->id_pengajuan)->delete();
                    LaporanAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->delete();
                    NilaiAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->delete();

                    // 3. Update Status ke non_aktif
                    $pengajuan->update([
                        'status' => 'non_aktif',
                        'catatan_verifikator' => 'SANKSI OTOMATIS: Status magang dinonaktifkan karena tidak mengunggah laporan mingguan sebanyak 4 kali.'
                    ]);

                    return redirect()->route('mahasiswa.laporan')->with('error', 'Sanksi: Laporan Anda telah direset dan akses dinonaktifkan.');
                }
            }
        }

        // Siapkan variabel pesanLock untuk ditampilkan di Blade (Level 1)
        $pesanLock = null;
        if ($pengajuan && $pengajuan->status === 'non_aktif') {
            $pesanLock = $pengajuan->catatan_verifikator;
        }

        // Ambil data untuk konten laporan jika statusnya disetujui
        $currentTab = request()->query('tab', 'mingguan');
        $laporanMingguan = collect();
        $laporanAkhir = collect();
        $nilaiAkhir = null;

        if ($pengajuan && $pengajuan->status === 'disetujui') {
            $laporanMingguan = LaporanMingguan::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->orderBy('minggu_ke', 'asc')->get();
            $laporanAkhir = LaporanAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->get();
            $nilaiAkhir = NilaiAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->first();
        }

        return view('dashboard.mahasiswa.laporan', compact(
            'pengajuan',
            'mahasiswa',
            'laporanMingguan',
            'laporanAkhir',
            'nilaiAkhir',
            'pesanLock'
        ));
    }

    // ========================================
    // LAPORAN MINGGUAN (Upload, View, Download, Delete)
    // ========================================
    public function uploadMingguan(Request $request)
    {
        $validated = $request->validate([
            'minggu_ke' => 'required|integer|min:1|max:52',
            'judul_laporan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();
            $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->where('status', 'disetujui')
                ->firstOrFail();

            // Cek Duplikasi
            $exists = LaporanMingguan::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->where('minggu_ke', $validated['minggu_ke'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Laporan minggu ke-' . $validated['minggu_ke'] . ' sudah ada!');
            }

            // Upload Proses
            $npm = $mahasiswa->npm;
            $ext = $request->file('file_laporan')->extension();
            $fileName = $npm . '_minggu_' . $validated['minggu_ke'] . '_' . time() . '.' . $ext;
            $filePath = $request->file('file_laporan')->storeAs('dokumen/laporan-mingguan', $fileName, 'public');

            LaporanMingguan::create([
                'id_laporan_mingguan' => (string) Str::uuid(),
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'minggu_ke' => $validated['minggu_ke'],
                'judul_laporan' => $validated['judul_laporan'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'tanggal_upload' => now(),
                'status' => 'pending',
            ]);

            return redirect()->route('mahasiswa.laporan', ['tab' => 'mingguan'])
                ->with('success', 'Laporan mingguan berhasil diunggah!');

        } catch (\Exception $e) {
            if (isset($filePath))
                Storage::disk('public')->delete($filePath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadMingguan($id)
    {
        $laporan = LaporanMingguan::with('pengajuan')->findOrFail($id);
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();

        if ($laporan->pengajuan->id_mhs != $mahasiswa->id_mhs)
            abort(403);

        $filePath = storage_path('app/public/' . $laporan->file_path);
        if (!file_exists($filePath))
            return back()->with('error', 'File tidak ditemukan');

        return response()->download($filePath);
    }

    public function deleteMingguan($id)
    {
        try {
            $laporan = LaporanMingguan::with('pengajuan')->findOrFail($id);
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();

            if ($laporan->pengajuan->id_mhs != $mahasiswa->id_mhs)
                abort(403);
            if ($laporan->status !== 'pending')
                return back()->with('error', 'Laporan tidak dapat dihapus!');

            Storage::disk('public')->delete($laporan->file_path);
            $laporan->delete();

            return back()->with('success', 'Laporan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========================================
    // LAPORAN AKHIR
    // ========================================
    public function uploadAkhir(Request $request)
    {
        $validated = $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();
            $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->where('status', 'disetujui')
                ->firstOrFail();

            if (LaporanAkhir::where('id_pengajuan', $pengajuan->id_pengajuan)->exists()) {
                return back()->with('error', 'Laporan akhir sudah pernah diunggah.');
            }

            $fileName = $mahasiswa->npm . '_laporan_akhir_' . time() . '.' . $request->file('file_laporan')->extension();
            $filePath = $request->file('file_laporan')->storeAs('dokumen/laporan-akhir', $fileName, 'public');

            LaporanAkhir::create([
                'id_laporan_akhir' => (string) Str::uuid(),
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'judul_laporan' => $validated['judul_laporan'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'tanggal_upload' => now(),
                'status' => 'pending',
            ]);

            return redirect()->route('mahasiswa.laporan', ['tab' => 'akhir'])
                ->with('success', 'Laporan akhir berhasil diunggah!');

        } catch (\Exception $e) {
            if (isset($filePath))
                Storage::disk('public')->delete($filePath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadAkhir($id)
    {
        $laporan = LaporanAkhir::findOrFail($id);
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();

        if ($laporan->pengajuan->id_mhs != $mahasiswa->id_mhs)
            abort(403);

        $filePath = storage_path('app/public/' . $laporan->file_path);
        if (!file_exists($filePath))
            return back()->with('error', 'File tidak ditemukan');

        return response()->download($filePath);
    }
}