<?php

namespace App\Http\Controllers\DosenPembimbing;

use App\Http\Controllers\Controller;
use App\Models\laporanmingguan;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanMahasiswaController extends Controller
{
    /**
     * Display a listing of laporan mahasiswa yang dibimbing, with search
     */
public function index(Request $request)
{
    $dosenId = DB::table('dosen_pembimbing')
        ->where('id_user', Auth::id())
        ->value('id_dosen');

    if (!$dosenId) {
        abort(403, 'Akun ini bukan dosen pembimbing');
    }

    $search = $request->query('search', '');
    $statusFilter = $request->query('status', ''); // Ini mengambil nilai dari <select name="status">

    $allLaporan = collect();

    // Logika Filter:
    // Jika status kosong (Semua), ambil keduanya.
    // Jika status = 'diajukan' (di HTML kamu valuenya 'diajukan' untuk Bulanan), ambil Bulanan saja.
    // Jika status = 'disetujui' (di HTML kamu valuenya 'disetujui' untuk Akhir), ambil Akhir saja.

    if ($statusFilter == 'diajukan') {
        // Hanya ambil Bulanan
        $allLaporan = $this->getlaporanmingguan($dosenId, $search);
    } elseif ($statusFilter == 'disetujui') {
        // Hanya ambil Akhir
        $allLaporan = $this->getLaporanAkhir($dosenId, $search);
    } else {
        // Ambil Semua (Default)
        $laporanmingguan = $this->getlaporanmingguan($dosenId, $search);
        $laporanAkhir = $this->getLaporanAkhir($dosenId, $search);
        $allLaporan = $laporanmingguan->concat($laporanAkhir);
    }

    // Urutkan dan Pagination
    $allLaporan = $allLaporan->sortByDesc('tanggal_upload')->values();

    $page = $request->get('page', 1);
    $perPage = 10;
    $paginated = new LengthAwarePaginator(
        $allLaporan->forPage($page, $perPage),
        $allLaporan->count(),
        $perPage,
        $page,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    return view('dashboard.dosen-pembimbing.laporan_mahasiswa', [
        'laporanList' => $paginated,
        'search' => $search,
    ]);
}

    /**
     * Ambil data laporan mingguan dengan search
     */
    private function getlaporanmingguan($dosenId, $search = '')
    {
        $query = laporanmingguan::with(['pengajuan.mahasiswa'])
            ->whereHas('pengajuan', fn($q) => $q->where('id_dosen', $dosenId));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengajuan.mahasiswa', function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%$search%")
                       ->orWhere('npm', 'like', "%$search%");
                })
                ->orWhere('judul_laporan', 'like', "%$search%");
            });
        }

        return $query->get()->map(fn($item) => [
            'id' => $item->id_laporan_mingguan,
            'tipe' => 'bulanan',
            'mahasiswa' => $item->pengajuan->mahasiswa,
            'judul_laporan' => $item->judul_laporan ?? 'laporan mingguan',
            'tanggal_upload' => $item->tanggal_upload,
            'status' => $item->status,
            'komentar_pembimbing' => $item->catatan_reviewer,
            'file_path' => $item->file_laporan,
        ]);
    }

    /**
     * Ambil data laporan akhir dengan search
     */
    private function getLaporanAkhir($dosenId, $search = '')
    {
        $query = LaporanAkhir::with(['pengajuan.mahasiswa'])
            ->whereHas('pengajuan', fn($q) => $q->where('id_dosen', $dosenId));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengajuan.mahasiswa', function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%$search%")
                       ->orWhere('npm', 'like', "%$search%");
                })
                ->orWhere('judul_laporan', 'like', "%$search%");
            });
        }

        return $query->get()->map(fn($item) => [
            'id' => $item->id_laporan_akhir,
            'tipe' => 'akhir',
            'mahasiswa' => $item->pengajuan->mahasiswa,
            'judul_laporan' => $item->judul_laporan ?? 'Laporan Akhir',
            'tanggal_upload' => $item->tanggal_upload,
            'status' => $item->status,
            'komentar_pembimbing' => $item->catatan_reviewer,
            'file_path' => $item->file_laporan,
        ]);
    }

    /**
     * Get detail laporan for modal
     */
    public function show(Request $request, $id)
    {
        $tipe = $request->query('tipe', 'bulanan');

        $laporan = $tipe === 'bulanan'
            ? laporanmingguan::with(['pengajuan.mahasiswa'])->find($id)
            : LaporanAkhir::with(['pengajuan.mahasiswa'])->find($id);

        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
        }

        $dosenId = DB::table('dosen_pembimbing')
            ->where('id_user', Auth::id())
            ->value('id_dosen');

        if ($laporan->pengajuan->id_dosen != $dosenId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        return response()->json(['success' => true, 'data' => $laporan]);
    }

    /**
     * Update review laporan
     */
    public function updateReview(Request $request, $id)
    {
        try {
            $data = $request->json()->all();

            if (!isset($data['tipe'], $data['status'], $data['catatan_reviewer'])) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 422);
            }

            $laporan = match($data['tipe']) {
                'bulanan' => laporanmingguan::find($id),
                'akhir' => LaporanAkhir::find($id),
                default => null
            };

            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
            }

            $laporan->update([
                'status' => $data['status'],
                'catatan_reviewer' => $data['catatan_reviewer'],
            ]);

            return response()->json(['success' => true, 'message' => 'Review berhasil disimpan']);
        } catch (\Throwable $e) {
            \Log::error('ERROR UPDATE REVIEW', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server'], 500);
        }
    }

    /**
     * Evaluasi laporan mingguan
     */
    public function evaluasiBulanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_reviewer' => 'nullable|string',
        ]);

        $laporan = laporanmingguan::find($id);

        if (!$laporan) return back()->with('error', 'laporan mingguan tidak ditemukan');

        $laporan->update([
            'status' => $request->status,
            'catatan_reviewer' => $request->catatan_reviewer,
        ]);

        return back()->with('success', 'Evaluasi laporan mingguan berhasil disimpan');
    }

    /**
     * Evaluasi laporan akhir
     */
    public function evaluasiAkhir(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_reviewer' => 'nullable|string',
        ]);

        $laporan = LaporanAkhir::find($id);

        if (!$laporan) return back()->with('error', 'Laporan akhir tidak ditemukan');

        $laporan->update([
            'status' => $request->status,
            'catatan_reviewer' => $request->catatan_reviewer,
        ]);

        return back()->with('success', 'Evaluasi laporan akhir berhasil disimpan');
    }

    /**
     * Download file laporan
     */
    public function download(Request $request, $id)
    {
        $tipe = $request->query('tipe', 'bulanan');

        $laporan = $tipe === 'bulanan'
            ? laporanmingguan::with('pengajuan')->find($id)
            : LaporanAkhir::with('pengajuan')->find($id);

        if (!$laporan) abort(404, 'Laporan tidak ditemukan');

        $dosenId = DB::table('dosen_pembimbing')
            ->where('id_user', Auth::id())
            ->value('id_dosen');

        if ($laporan->pengajuan->id_dosen != $dosenId) abort(403, 'Unauthorized access');

        $filePath = storage_path('app/public/' . $laporan->file_laporan);

        if (!file_exists($filePath)) abort(404, 'File tidak ditemukan');

        return response()->download($filePath);
    }
}
