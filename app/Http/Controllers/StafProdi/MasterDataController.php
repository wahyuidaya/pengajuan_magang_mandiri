<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\MitraPerusahaan;
use App\Http\Controllers\StafProdi\ActivityLogController;

class MasterDataController extends Controller
{
    /**
     * Tampilkan halaman data mahasiswa
     */
public function index(Request $request)
{
    try {
        $search = $request->query('search');
        $statusFilter = $request->query('status'); 

        $mahasiswaList = Mahasiswa::with([
            'pengajuanMagang' => function ($query) use ($statusFilter) {
                $query->with(['mitra', 'dosenPembimbing']);
                
                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                } else {
                    $query->whereIn('status', ['disetujui', 'non_aktif']);
                }
            }
        ])
        ->whereHas('pengajuanMagang', function ($query) use ($statusFilter) {
            if ($statusFilter) {
                $query->where('status', $statusFilter);
            } else {
                $query->whereIn('status', ['disetujui', 'non_aktif']);
            }
        })
        ->where('aktif', true)
        ->when($search, function($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%");
            });
        })
        /* --- PERUBAHAN DI SINI --- */
        ->orderBy('nama_lengkap', 'asc') 
        /* ------------------------- */
        ->paginate(10)
        ->appends($request->all());

        $dosenList = DosenPembimbing::orderBy('nama_dosen', 'asc')->get();
        $mitraList = MitraPerusahaan::orderBy('nama_perusahaan', 'asc')->get();

        return view('dashboard.staf-prodi.staf_prodi_data_mahasiswa', compact('mahasiswaList', 'dosenList', 'mitraList'));

    } catch (\Exception $e) {
        \Log::error('Error loading data mahasiswa: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data mahasiswa.');
    }
}

    /**
     * Mengaktifkan kembali pengajuan yang non_aktif
        */
    public function activate($id)
    {
        try {
            DB::beginTransaction();

            $pengajuan = PengajuanMagang::findOrFail($id);
            
            // Hanya mengubah status menjadi disetujui, data tetap ada
            $pengajuan->update([
                'status' => 'disetujui',
                'catatan_verifikator' => null 
            ]);

            DB::commit();

            ActivityLogController::saveLog('Aktivasi Pengajuan', "Mengaktifkan kembali pengajuan ID: {$id}");

            return redirect()->back()->with('success', 'Pengajuan mahasiswa berhasil diaktifkan kembali.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error activating pengajuan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengaktifkan pengajuan.');
        }
    }


    /**
     * Get detail mahasiswa by NPM (AJAX)
     */
public function getDetail($npm)
{
    try {
        $mahasiswa = Mahasiswa::with([
            'pengajuanMagang' => function ($query) {
                $query->whereIn('status', ['disetujui', 'non_aktif'])
                    ->with(['mitra', 'laporanmingguan', 'laporanAkhir', 'nilaiAkhir']);
            }
        ])->where('npm', $npm)->firstOrFail();

        $pengajuan = $mahasiswa->pengajuanMagang->first();

        return response()->json([
            'success' => true,
            'data' => [
                'nama_lengkap' => $mahasiswa->nama_lengkap,
                'npm' => $mahasiswa->npm,
                'kelas' => $mahasiswa->kelas ?? '-',
                'ipk' => $mahasiswa->ipk ?? '-',
                'no_hp' => $mahasiswa->no_hp ?? '-',
                'perusahaan' => optional($pengajuan->mitra)->nama_perusahaan ?? '-',
                'periode_magang' => $pengajuan->periode_magang ?? '-', // ✅ TAMBAHKAN INI
                'dosen_pembimbing' => $pengajuan->id_dosen
                    ? DosenPembimbing::find($pengajuan->id_dosen)->nama_dosen ?? '-'
                    : '-',
                'id_dosen' => $pengajuan->id_dosen ?? null,
                'file_penilaian_mitra' => $pengajuan->penilaianMagang
                    ? asset('storage/' . $pengajuan->penilaianMagang->file_penilaian)
                    : null,
                'nilai_akhir' => $pengajuan->nilaiAkhir->nilai_huruf ?? '-',
                'id_pengajuan' => $pengajuan->id_pengajuan ?? null,
                'krs_url' => $pengajuan->krs_url ? asset('storage/' . $pengajuan->krs_url) : null,
                'transkrip_url' => $pengajuan->transkrip_url ? asset('storage/' . $pengajuan->transkrip_url) : null,
                'surat_pengantar_mahasiswa' => $pengajuan->surat_pengantar_mahasiswa
                    ? asset('storage/' . $pengajuan->surat_pengantar_mahasiswa)
                    : null,
                'laporan_mingguan_url' => $pengajuan->laporanmingguan->count()
                    ? asset('storage/' . $pengajuan->laporanmingguan->last()->file_path)
                    : null,
                'laporan_akhir_url' => $pengajuan->laporanAkhir
                    ? asset('storage/' . $pengajuan->laporanAkhir->file_path)
                    : null,
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error getting mahasiswa detail: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Data mahasiswa tidak ditemukan.'
        ], 404);
    }
}

    /**
     * Update dosen pembimbing - FIXED VERSION
     */
    public function updateDosenPembimbing(Request $request)
    {
        // Log incoming request
        Log::info('Update Dosen Request', [
            'request_data' => $request->all()
        ]);

        try {
            // Validasi input
            $validated = $request->validate([
                'id_pengajuan' => 'required|string',
                'id_dosen' => 'required|string',
            ]);

            Log::info('Validation passed', $validated);

            // Cari pengajuan
            $pengajuan = PengajuanMagang::where('id_pengajuan', $validated['id_pengajuan'])->first();

            if (!$pengajuan) {
                Log::error('Pengajuan not found', ['id_pengajuan' => $validated['id_pengajuan']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengajuan tidak ditemukan.'
                ], 404);
            }

            // Cari dosen
            $dosen = DosenPembimbing::where('id_dosen', $validated['id_dosen'])->first();

            if (!$dosen) {
                Log::error('Dosen not found', ['id_dosen' => $validated['id_dosen']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan.'
                ], 404);
            }

            // Update pengajuan
            DB::beginTransaction();

            $pengajuan->id_dosen = $validated['id_dosen'];
            $pengajuan->save();

            DB::commit();

            ActivityLogController::saveLog('Update Dosen Pembimbing', "Mengubah Dosen Pengajuan ID: {$pengajuan->id_pengajuan} ke {$dosen->nama_dosen}");

            Log::info('Dosen updated successfully', [
                'pengajuan_id' => $pengajuan->id_pengajuan,
                'dosen_id' => $dosen->id_dosen,
                'dosen_nama' => $dosen->nama_dosen
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dosen pembimbing berhasil diperbarui!',
                'data' => [
                    'nama_dosen' => $dosen->nama_dosen
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', collect($e->errors())->flatten()->toArray())
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating dosen pembimbing', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMitra(Request $request)
    {
        $validated = $request->validate([
            'id_pengajuan' => 'required|string',
            'id_mitra' => 'required|string',
        ]);

        $pengajuan = PengajuanMagang::find($validated['id_pengajuan']);
        $mitra = MitraPerusahaan::find($validated['id_mitra']);

        if (!$pengajuan || !$mitra) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $pengajuan->id_mitra = $mitra->id_mitra;
        $pengajuan->save();

        ActivityLogController::saveLog('Update Mitra Magang', "Mengubah Mitra Pengajuan ID: {$pengajuan->id_pengajuan} ke {$mitra->nama_perusahaan}");

        return response()->json(['success' => true, 'data' => ['nama_perusahaan' => $mitra->nama_perusahaan]]);
    }


    /**
     * Update nilai akhir mahasiswa
     */
    public function updateNilaiAkhir(Request $request)
    {
        Log::info('Update Nilai Request', ['request_data' => $request->all()]);

        try {
            $validated = $request->validate([
                'id_pengajuan' => 'required|string',
                'nilai_akhir' => 'required|string|max:2',
            ]);

            $pengajuan = PengajuanMagang::where('id_pengajuan', $validated['id_pengajuan'])->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengajuan tidak ditemukan.'
                ], 404);
            }

            $nilaiValid = ['A', 'B', 'C', 'D', 'E', 'A-', 'B+', 'B-', 'C+', 'C-', 'D+'];
            $nilaiInput = strtoupper(trim($validated['nilai_akhir']));

            if (!in_array($nilaiInput, $nilaiValid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format nilai tidak valid. Gunakan: A, B, C, D, atau E.'
                ], 422);
            }

            DB::beginTransaction();

            $pengajuan->nilai_akhir = $nilaiInput;
            $pengajuan->save();

            DB::commit();

            ActivityLogController::saveLog('Update Nilai Akhir', "Input Nilai {$nilaiInput} untuk Pengajuan ID: {$pengajuan->id_pengajuan}");

            Log::info('Nilai akhir updated successfully', [
                'pengajuan_id' => $pengajuan->id_pengajuan,
                'nilai_akhir' => $nilaiInput
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nilai akhir berhasil diperbarui!',
                'data' => [
                    'nilai_akhir' => $nilaiInput
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating nilai akhir: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download dokumen mahasiswa
     */
    public function downloadDokumen($idPengajuan, $jenis)
    {
        try {
            $pengajuan = PengajuanMagang::with('mahasiswa')->findOrFail($idPengajuan);

            $dokumen = [
                'krs' => [
                    'path' => $pengajuan->krs_url,
                    'name' => 'KRS_' . $pengajuan->mahasiswa->npm . '.pdf'
                ],
                'transkrip' => [
                    'path' => $pengajuan->transkrip_url,
                    'name' => 'Transkrip_' . $pengajuan->mahasiswa->npm . '.pdf'
                ],
                'surat' => [
                    'path' => $pengajuan->surat_pengantar_mahasiswa,
                    'name' => 'SuratPengantar_' . $pengajuan->mahasiswa->npm . '.pdf'
                ],
                'laporan_mingguan' => [
                    'path' => optional($pengajuan->laporanmingguan->last())->file_path,
                    'name' => 'laporanmingguan_' . $pengajuan->mahasiswa->npm . '.pdf'
                ],
                'laporan_akhir' => [
                    'path' => optional($pengajuan->laporanAkhir)->file_path,
                    'name' => 'LaporanAkhir_' . $pengajuan->mahasiswa->npm . '.pdf'
                ],
            ];

            if (!isset($dokumen[$jenis])) {
                return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
            }

            $filePath = $dokumen[$jenis]['path'];
            $fileName = $dokumen[$jenis]['name'];

            if (!$filePath || !\Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            $fullPath = \Storage::disk('public')->path($filePath);

            ActivityLogController::saveLog('Download Dokumen Mahasiswa', "Mendownload {$jenis} mahasiswa NPM: {$pengajuan->mahasiswa->npm}");
            
            return response()->download($fullPath, $fileName);

        } catch (\Exception $e) {
            Log::error('Error downloading dokumen: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunduh dokumen.');
        }
    }
}