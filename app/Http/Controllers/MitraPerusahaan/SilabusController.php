<?php

namespace App\Http\Controllers\MitraPerusahaan;

use App\Http\Controllers\Controller;
use App\Models\SilabusMitra;
use App\Models\PengajuanMagang;
use App\Models\MitraPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SilabusController extends Controller
{
    /**
     * Tampilkan halaman kirim silabus dengan daftar mahasiswa
     */
public function index(Request $request)
{
    $user = Auth::user();
    $mitra = MitraPerusahaan::where('id_user', $user->id_user)->first();

    if (!$mitra) {
        return redirect()->route('dashboard')->with('error', 'Data mitra tidak ditemukan');
    }

    $query = PengajuanMagang::where('id_mitra', $mitra->id_mitra)
        ->where('status', 'disetujui')
        ->with(['mahasiswa', 'silabusMitra'])
        ->orderBy('tanggal_pengajuan', 'desc');

    // Tambahkan filter search jika ada input
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->whereHas('mahasiswa', function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('npm', 'like', "%{$search}%");
        });
    }

    $pengajuanList = $query->paginate(10)->withQueryString();

    return view('dashboard.mitra-perusahaan.kirim_silabus', compact('pengajuanList'));
}


    /**
     * Upload dan kirim silabus ke mahasiswa
     */
    public function uploadSilabus(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_pengajuan' => 'required|exists:pengajuan_magang,id_pengajuan',
                'file_silabus' => 'required|file|mimes:pdf,docx|max:10240', // Max 10MB
            ], [
                'file_silabus.required' => 'File silabus harus diunggah',
                'file_silabus.mimes' => 'File harus berformat PDF atau DOCX',
                'file_silabus.max' => 'Ukuran file maksimal 10MB',
            ]);

            // Get mitra yang sedang login
            $user = Auth::user();
            $mitra = MitraPerusahaan::where('id_user', $user->id_user)->first();

            if (!$mitra) {
                return redirect()->back()->with('error', 'Data mitra tidak ditemukan');
            }

            // Get pengajuan
            $pengajuan = PengajuanMagang::with('mahasiswa')
                ->where('id_pengajuan', $request->id_pengajuan)
                ->where('id_mitra', $mitra->id_mitra)
                ->where('status', 'disetujui')
                ->first();

            if (!$pengajuan) {
                return redirect()->back()->with('error', 'Pengajuan tidak valid atau belum disetujui');
            }

            // Cek apakah sudah ada silabus
            $silabus = SilabusMitra::where('id_pengajuan', $pengajuan->id_pengajuan)->first();

            if ($silabus) {
                return redirect()->back()->with('error', 'Silabus sudah pernah dikirim sebelumnya');
            }

            // Upload file
            $file = $request->file('file_silabus');
            $fileName = 'silabus_' . $pengajuan->id_pengajuan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('silabus_mitra', $fileName, 'public');

            // Buat record silabus baru
            $silabus = SilabusMitra::create([
                'id_silabus' => 'SIL-' . Str::random(10),
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_mitra' => $mitra->id_mitra,
                'judul_silabus' => 'Silabus untuk ' . $pengajuan->mahasiswa->nama,
                'file_silabus' => $filePath,
                'deskripsi' => 'Silabus dikirim ke mahasiswa ' . $pengajuan->mahasiswa->nama,
                'status' => 'terkirim',
                'tanggal_upload' => now()
            ]);

            return redirect()->back()->with('success', 'Silabus berhasil dikirim ke ' . $pengajuan->mahasiswa->nama);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali file yang Anda upload.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Kirim silabus ke mahasiswa (AJAX) - DEPRECATED
     * Gunakan uploadSilabus() sebagai gantinya
     */
    public function kirimSilabus(Request $request)
    {
        try {
            $request->validate([
                'id_pengajuan' => 'required|exists:pengajuan_magang,id_pengajuan',
            ]);

            $user = Auth::user();
            $mitra = MitraPerusahaan::where('id_user', $user->id_user)->first();

            if (!$mitra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mitra tidak ditemukan'
                ], 404);
            }

            $pengajuan = PengajuanMagang::with('mahasiswa')
                ->where('id_pengajuan', $request->id_pengajuan)
                ->where('id_mitra', $mitra->id_mitra)
                ->where('status', 'disetujui')
                ->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak valid atau belum disetujui'
                ], 404);
            }

            $silabus = SilabusMitra::where('id_pengajuan', $pengajuan->id_pengajuan)->first();

            if ($silabus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silabus sudah pernah dikirim sebelumnya'
                ], 400);
            }

            $silabus = SilabusMitra::create([
                'id_silabus' => 'SIL-' . Str::random(10),
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_mitra' => $mitra->id_mitra,
                'judul_silabus' => 'Silabus untuk ' . $pengajuan->mahasiswa->nama,
                'file_silabus' => null,
                'deskripsi' => 'Silabus dikirim ke mahasiswa',
                'status' => 'terkirim',
                'tanggal_upload' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Silabus berhasil dikirim ke ' . $pengajuan->mahasiswa->nama,
                'data' => [
                    'tanggal_kirim' => now()->format('Y-m-d'),
                    'nama_mahasiswa' => $pengajuan->mahasiswa->nama
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status pengiriman silabus untuk mahasiswa tertentu
     */
    public function getStatus($id_pengajuan)
    {
        try {
            $silabus = SilabusMitra::where('id_pengajuan', $id_pengajuan)->first();

            if ($silabus) {
                return response()->json([
                    'success' => true,
                    'terkirim' => true,
                    'tanggal' => date('Y-m-d', strtotime($silabus->tanggal_upload))
                ]);
            }

            return response()->json([
                'success' => true,
                'terkirim' => false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }
}