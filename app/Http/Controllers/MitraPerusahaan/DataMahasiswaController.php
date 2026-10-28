<?php

namespace App\Http\Controllers\MitraPerusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use App\Models\MitraPerusahaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DataMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $mitra = MitraPerusahaan::where('id_user', $user->id_user)->first();

        if (!$mitra) {
            return redirect()->route('dashboard.mitra-perusahaan')
                ->with('error', 'Data mitra tidak ditemukan');
        }

        $query = PengajuanMagang::with(['mahasiswa'])
            ->where('id_mitra', $mitra->id_mitra)
            ->where('status', 'disetujui');

        if ($search = $request->search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('npm', 'like', "%$search%");
            });
        }

        $mahasiswaMagang = $query->paginate(10)->through(function ($p) {
            return [
                'id_pengajuan' => $p->id_pengajuan,
                'nama' => $p->mahasiswa->nama_lengkap,
                'npm' => $p->mahasiswa->npm,
                'periode' => $p->periode_magang ?: null,
                'posisi' => $p->posisi_dilamar,
                'status' => now()->gt($p->tanggal_pengajuan->addMonths(3)) ? 'Selesai' : 'Aktif',
                'prodi' => $p->mahasiswa->prodi,
                'foto_url' => $p->mahasiswa->foto_url
            ];
        });

        return view(
            'dashboard.mitra-perusahaan.mitra_perusahaan_data_mahasiswa',
            compact('mahasiswaMagang')
        );
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    // ✅ INI YANG TADI SALAH POSISI
public function updatePeriode(Request $request)
{
    try {
        $validated = $request->validate([
            'id_pengajuan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $pengajuan = PengajuanMagang::where('id_pengajuan', $validated['id_pengajuan'])->first();

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Format tanggal agar rapi saat disimpan di kolom periode_magang (Varchar)
        $mulai = \Carbon\Carbon::parse($validated['tanggal_mulai'])->translatedFormat('d M Y');
        $selesai = \Carbon\Carbon::parse($validated['tanggal_selesai'])->translatedFormat('d M Y');
        
        $periodeGabungan = $mulai . ' - ' . $selesai;

        $pengajuan->periode_magang = $periodeGabungan;
        $pengajuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Periode berhasil disimpan',
            'periode_tampil' => $periodeGabungan
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


}
