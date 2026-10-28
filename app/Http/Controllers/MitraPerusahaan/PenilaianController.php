<?php

namespace App\Http\Controllers\MitraPerusahaan;

use App\Http\Controllers\Controller;
use App\Models\PenilaianMagang;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenilaianController extends Controller
{
    /**
     * Tampilkan halaman penilaian dengan data dari database
     */
public function index(Request $request)
{
    $idMitra = Auth::user()->mitra?->id_mitra;

    if (!$idMitra) {
        return redirect()->route('dashboard.mitra-perusahaan')
            ->with('error', 'Data mitra tidak ditemukan');
    }

    // Query dasar
    $query = PengajuanMagang::with(['mahasiswa', 'penilaian'])
        ->where('id_mitra', $idMitra)
        ->where('status', 'disetujui')
        ->orderBy('tanggal_pengajuan', 'desc');

    // Filter search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('mahasiswa', function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%$search%")
              ->orWhere('npm', 'like', "%$search%");
        });
    }

    // Filter status berdasarkan file_penilaian
if ($request->filled('status')) {
    if ($request->status === 'belum') {
        // Belum dinilai → file_penilaian masih null
        $query->where(function ($q) {
            $q->whereDoesntHave('penilaian') // pengajuan belum punya penilaian sama sekali
              ->orWhereHas('penilaian', function ($q2) {
                  $q2->whereNull('file_penilaian'); // atau punya penilaian tapi file_penilaian masih null
              });
        });
    } elseif ($request->status === 'sudah') {
        // Sudah dinilai → file_penilaian sudah ada
        $query->whereHas('penilaian', function ($q) {
            $q->whereNotNull('file_penilaian');
        });
    }
}

    // Pagination
    $pengajuanList = $query->paginate(10)->withQueryString();

    return view('dashboard.mitra-perusahaan.penilaian', compact('pengajuanList'));
}


    /**
     * Upload file penilaian
     */
    public function upload(Request $request)
    {
        $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan_magang,id_pengajuan',
            'file_penilaian' => 'required|file|mimes:xlsx,xls,pdf,doc,docx|max:10240', // max 10MB
        ]);

        try {
            $idPengajuan = $request->id_pengajuan;
            
            // Cek apakah pengajuan milik mitra yang login
            $pengajuan = PengajuanMagang::with('mahasiswa')
                ->where('id_pengajuan', $idPengajuan)
                ->where('id_mitra', Auth::user()->mitra->id_mitra)
                ->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan atau bukan milik Anda'
                ], 403);
            }

            // Upload file
            $file = $request->file('file_penilaian');
            $fileName = 'penilaian_' . $pengajuan->mahasiswa->npm . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('penilaian', $fileName, 'public');

            // Cek apakah sudah ada penilaian
            $penilaian = PenilaianMagang::where('id_pengajuan', $idPengajuan)->first();

            if ($penilaian) {
                // Update penilaian existing
                // Hapus file lama jika ada
                if ($penilaian->file_penilaian && Storage::disk('public')->exists($penilaian->file_penilaian)) {
                    Storage::disk('public')->delete($penilaian->file_penilaian);
                }

                $penilaian->file_penilaian = $filePath;
                $penilaian->tanggal_upload_mitra = now();
                $penilaian->save();

            } else {
                // Buat penilaian baru
                $penilaian = new PenilaianMagang();
                $penilaian->forceFill([
                    'id_penilaian' => 'NILAI-' . strtoupper(Str::random(10)),
                    'id_pengajuan' => $idPengajuan,
                    'file_penilaian' => $filePath,
                    'tanggal_upload_mitra' => now(),
                ])->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'File penilaian berhasil diunggah',
                'data' => [
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'tanggal_upload' => $penilaian->tanggal_upload_mitra->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus file penilaian
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan_magang,id_pengajuan',
        ]);

        try {
            $penilaian = PenilaianMagang::whereHas('pengajuan', function($query) {
                $query->where('id_mitra', Auth::user()->mitra->id_mitra);
            })->where('id_pengajuan', $request->id_pengajuan)->first();

            if (!$penilaian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penilaian tidak ditemukan'
                ], 404);
            }

            // Hapus file dari storage
            if ($penilaian->file_penilaian && Storage::disk('public')->exists($penilaian->file_penilaian)) {
                Storage::disk('public')->delete($penilaian->file_penilaian);
            }

            // Reset data penilaian
            $penilaian->update([
                'file_penilaian' => null,
                'tanggal_upload_mitra' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File penilaian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }
}