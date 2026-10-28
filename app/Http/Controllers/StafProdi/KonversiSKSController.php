<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\KonversiSks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\StafProdi\ActivityLogController;

class KonversiSksController extends Controller
{
    /* =====================================================
     * HALAMAN DAFTAR MAHASISWA KONVERSI
     * ===================================================== */
    public function index(Request $request)
    {
        $query = PengajuanMagang::with([
                'mahasiswa',
                'mitra',
                'dosenPembimbing'
            ])->where('status', 'disetujui') // Hanya pengajuan disetujui
            ->whereNotNull('silabus_url')
            ->orderBy('tanggal_pengajuan', 'desc');

        // ===============================
        // FILTER SEARCH
        // ===============================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%");
            });
        }

        // ===============================
        // FILTER STATUS KONVERSI
        // ===============================
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('konversiSks', function ($q) use ($status) {
                if ($status === 'belum') {
                    $q->havingRaw('COUNT(*) = 0'); // Belum ada MK konversi
                } elseif ($status === 'draft') {
                    $q->where('status', 'draft');
                } elseif ($status === 'selesai') {
                    $q->where('status', 'disetujui');
                }
            });
        }

        $pengajuanList = $query->paginate(10)->withQueryString(); // preserve query param search/status

        return view('dashboard.staf-prodi.konversi_sks_mahasiswa', compact('pengajuanList'));
    }


    /* =====================================================
     * GET DATA DETAIL (AJAX MODAL)
     * ===================================================== */
    public function getData($id_pengajuan)
    {
        $pengajuan = PengajuanMagang::with([
            'mahasiswa',
            'mitra',
            'dosenPembimbing'
        ])->where('id_pengajuan', $id_pengajuan)
          ->firstOrFail();

        $konversiList = KonversiSks::where('id_pengajuan', $id_pengajuan)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'mahasiswa' => [
                'id_mhs' => $pengajuan->mahasiswa->id_mhs,
                'npm' => $pengajuan->mahasiswa->npm,
                'nama' => $pengajuan->mahasiswa->nama_lengkap,
            ],
            'perusahaan' => $pengajuan->mitra->nama_perusahaan ?? '-',

            // ✅ DOSEN PEMBIMBING
            'dosen' => $pengajuan->dosenPembimbing ? [
                'nama' => $pengajuan->dosenPembimbing->nama_dosen,
                'email' => $pengajuan->dosenPembimbing->email,
            ] : null,

            // ✅ KRS (INI YANG SEBELUMNYA HILANG)
            'krs_url' => $pengajuan->krs_url
                ? asset('storage/' . $pengajuan->krs_url)
                : null,

            // (kalau silabus masih dipakai)
            'silabus_url' => $pengajuan->silabus_url
                ? asset('storage/' . $pengajuan->silabus_url)
                : null,

            'konversi' => $konversiList,
        ]);
    }

    /* =====================================================
     * UPLOAD FILE KONVERSI (NEW METHOD)
     * ===================================================== */
    public function upload(Request $request, $id_pengajuan)
    {
        // Validasi
        $request->validate([
            'file_konversi' => 'required|file|mimes:pdf,doc,docx,xlsx,xls|max:10240', // max 10MB
        ], [
            'file_konversi.required' => 'File konversi harus diupload',
            'file_konversi.mimes' => 'File harus berformat PDF, Word, atau Excel',
            'file_konversi.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            // Cek apakah pengajuan ada
            $pengajuan = PengajuanMagang::findOrFail($id_pengajuan);

            // Upload file
            $file = $request->file('file_konversi');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/konversi_sks
            $path = $file->storeAs('konversi_sks', $fileName, 'public');

            // Simpan ke database
            KonversiSks::create([
                'id_konversi' => Str::uuid(),
                'id_pengajuan' => $id_pengajuan,
                'nama_file' => $originalName,
                'path_file' => $path,
                'uploaded_by' => Auth::id(),
                'created_at' => now(),
            ]);

            ActivityLogController::saveLog('Upload File Konversi', "Mengunggah file: {$originalName} untuk Pengajuan ID: {$id_pengajuan}");

            return redirect()->back()->with('success', 'File konversi SKS berhasil diupload');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /* =====================================================
     * TAMBAH MATA KULIAH KONVERSI
     * ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan_magang,id_pengajuan',
            'id_mhs'       => 'required|exists:mahasiswa,id_mhs',
            'kode_mk'      => 'required|string|max:20',
            'nama_mk'      => 'required|string|max:100',
            'sks'          => 'required|integer|min:1|max:6',
        ]);

        KonversiSks::create([
            'id_konversi' => Str::uuid(),
            'id_pengajuan' => $request->id_pengajuan,
            'id_mhs'       => $request->id_mhs,
            'kode_mk'      => $request->kode_mk,
            'nama_mk'      => $request->nama_mk,
            'sks'          => $request->sks,
            'status'       => 'draft',
        ]);

        ActivityLogController::saveLog('Tambah MK Konversi', "Menambah MK: {$request->nama_mk} ({$request->kode_mk}) - {$request->sks} SKS");

        return back()->with('success', 'Mata kuliah konversi berhasil ditambahkan');
    }

    /* =====================================================
     * UPDATE KONVERSI
     * ===================================================== */
    public function update(Request $request, $id_konversi)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20',
            'nama_mk' => 'required|string|max:100',
            'sks'     => 'required|integer|min:1|max:6',
        ]);

        $konversi = KonversiSks::findOrFail($id_konversi);

        $konversi->update([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks'     => $request->sks,

            // 🔴 RESET STATUS
            'status' => 'draft',
            'disetujui_oleh' => null,
            'tanggal_disetujui' => null,
        ]);

        ActivityLogController::saveLog('Update MK Konversi', "Mengubah MK ID: {$id_konversi} menjadi {$request->nama_mk}");

        return back()->with('success', 'Data diperbarui, silakan setujui ulang');
    }

    /* =====================================================
     * HAPUS KONVERSI
     * ===================================================== */
        public function destroy($id_konversi)
        {
            $konversi = KonversiSks::findOrFail($id_konversi);
            ActivityLogController::saveLog('Hapus MK Konversi', "Menghapus MK: {$konversi->nama_mk} ({$konversi->kode_mk})");
            $konversi->delete();
            return back()->with('success', 'Mata kuliah konversi berhasil dihapus');
        }

    /* =====================================================
     * APPROVE SEMUA KONVERSI
     * ===================================================== */
    public function approve($id_pengajuan)
    {
        $list = KonversiSks::where('id_pengajuan', $id_pengajuan)
            ->where('status', 'draft')
            ->get();

        if ($list->isEmpty()) {
            return back()->with('error', 'Tidak ada konversi yang perlu disetujui');
        }

        foreach ($list as $item) {
            $item->update([
                'status'            => 'disetujui',
                'disetujui_oleh'    => Auth::id(),
                'tanggal_disetujui'=> now(),
            ]);
        }

        ActivityLogController::saveLog('Setujui Semua Konversi', "Menyetujui semua MK konversi untuk Pengajuan ID: {$id_pengajuan}");

        return back()->with('success', 'Semua Konversi SKS Mahasiswa berhasil disetujui');
    }
    
    /* =====================================================
     * APPROVE SATU KONVERSI
     * ===================================================== */
    public function approveOne($id_konversi)
    {
        $konversi = KonversiSks::findOrFail($id_konversi);

        if ($konversi->status === 'disetujui') {
            return back()->with('error', 'Mata kuliah sudah disetujui');
        }

        $konversi->update([
            'status' => 'disetujui',
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now(),
        ]);

        ActivityLogController::saveLog('Setujui Satu MK Konversi', "Menyetujui MK: {$konversi->nama_mk}");

        return back()->with('success', 'Mata kuliah berhasil disetujui');
    }

    /* =====================================================
     * DOWNLOAD SILABUS
     * ===================================================== */
    public function downloadSilabus($id_pengajuan)
    {
        $pengajuan = PengajuanMagang::findOrFail($id_pengajuan);

        if (!$pengajuan->silabus_url) {
            return back()->with('error', 'Silabus tidak tersedia');
        }

        ActivityLogController::saveLog('Download Silabus', "Mendownload silabus Pengajuan ID: {$id_pengajuan}");

        return response()->download(
            storage_path('app/public/' . $pengajuan->silabus_url)
        );
    }
}