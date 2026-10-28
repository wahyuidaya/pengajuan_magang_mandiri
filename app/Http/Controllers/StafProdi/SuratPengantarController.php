<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StafProdi\ActivityLogController;

class SuratPengantarController extends Controller
{
    /**
     * Tampilkan halaman surat pengantar
     */
    public function index(Request $request)
    {
        try {
            $query = PengajuanMagang::where('status', 'disetujui')
                ->with(['mahasiswa', 'mitra', 'dosenPembimbing']);

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('npm', 'like', "%{$search}%");
                });
            }

            $pengajuanList = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(10);

            return view('dashboard.staf-prodi.surat_pengantar', compact('pengajuanList'));

        } catch (\Exception $e) {
            return view('dashboard.staf-prodi.surat_pengantar', [
                'pengajuanList' => collect([]),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload surat pengantar dari staf prodi
     */
    public function upload(Request $request, $id_pengajuan)
    {
        try {
            $request->validate([
                'surat_pengantar' => 'required|file|mimes:pdf,docx|max:10240' // Max 10MB
            ], [
                'surat_pengantar.required' => 'File surat pengantar wajib diupload',
                'surat_pengantar.mimes' => 'Format file harus PDF atau DOCX',
                'surat_pengantar.max' => 'Ukuran file maksimal 10MB'
            ]);

            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id_pengajuan);

            // Validasi status pengajuan
            if ($pengajuan->status !== 'disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat pengantar hanya bisa diupload untuk pengajuan yang sudah disetujui'
                ], 422);
            }

            // Validasi apakah mahasiswa sudah upload surat pengantar
            if (empty($pengajuan->surat_pengantar_mahasiswa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa belum mengupload surat pengantar. Mohon minta mahasiswa upload terlebih dahulu.'
                ], 422);
            }

            // Hapus file lama jika ada
            if ($pengajuan->surat_pengantar_prodi && Storage::disk('public')->exists($pengajuan->surat_pengantar_prodi)) {
                Storage::disk('public')->delete($pengajuan->surat_pengantar_prodi);
            }

            // Upload file baru
            $file = $request->file('surat_pengantar');
            $npm = $pengajuan->mahasiswa->npm ?? 'unknown';
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $filename = "{$npm}_prodi_{$timestamp}.{$extension}";
            
            $path = $file->storeAs('dokumen/surat_pengantar/prodi', $filename, 'public');

            // Update database
            $pengajuan->surat_pengantar_prodi = $path;
            $pengajuan->save();

            ActivityLogController::saveLog(
                'Upload Surat Pengantar', 
                "Mengupload surat pengantar prodi untuk mahasiswa: {$pengajuan->mahasiswa->nama_lengkap} ({$pengajuan->mahasiswa->npm})"
            );

            return response()->json([
                'success' => true,
                'message' => 'Surat pengantar berhasil diupload!',
                'data' => [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => Storage::url($path)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download surat pengantar dari prodi
     */
    public function download($id_pengajuan)
    {
        try {
            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id_pengajuan);

            if (empty($pengajuan->surat_pengantar_prodi)) {
                return back()->with('error', 'Surat pengantar dari prodi belum diupload');
            }

            $filePath = storage_path('app/public/' . $pengajuan->surat_pengantar_prodi);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $npm = $pengajuan->mahasiswa->npm ?? 'unknown';
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = "Surat_Pengantar_Prodi_{$npm}.{$extension}";

            ActivityLogController::saveLog(
                'Download Surat Pengantar', 
                "Mengunduh surat pengantar prodi milik: {$pengajuan->mahasiswa->nama_lengkap}"
            );

            return response()->download($filePath, $filename);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Pengajuan tidak ditemukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download surat pengantar dari mahasiswa
     */
    public function downloadDraft($id_pengajuan)
    {
        try {
            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($id_pengajuan);

            if (empty($pengajuan->surat_pengantar_mahasiswa)) {
                return back()->with('error', 'Mahasiswa belum mengupload surat pengantar');
            }

            $filePath = storage_path('app/public/' . $pengajuan->surat_pengantar_mahasiswa);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan');
            }

            $npm = $pengajuan->mahasiswa->npm ?? 'unknown';
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = "Surat_Pengantar_Mahasiswa_{$npm}.{$extension}";

            ActivityLogController::saveLog(
                'Download Draft Surat', 
                "Mengunduh draft surat pengantar dari mahasiswa: {$pengajuan->mahasiswa->nama_lengkap}"
            );

            return response()->download($filePath, $filename);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Pengajuan tidak ditemukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus surat pengantar dari prodi
     */
    public function delete($id_pengajuan)
    {
        try {
            $pengajuan = PengajuanMagang::findOrFail($id_pengajuan);

            if (empty($pengajuan->surat_pengantar_prodi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada surat pengantar yang perlu dihapus'
                ], 404);
            }

            // Hapus file dari storage
            if (Storage::disk('public')->exists($pengajuan->surat_pengantar_prodi)) {
                Storage::disk('public')->delete($pengajuan->surat_pengantar_prodi);
            }

            // Update database
            $pengajuan->surat_pengantar_prodi = null;
            $pengajuan->save();

            ActivityLogController::saveLog(
                'Hapus Surat Pengantar', 
                "Menghapus surat pengantar prodi milik pengajuan ID: {$id_pengajuan}"
            );

            return response()->json([
                'success' => true,
                'message' => 'Surat pengantar berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}