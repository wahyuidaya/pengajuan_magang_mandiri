<?php

namespace App\Http\Controllers\DosenPembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanMagang;
use App\Models\laporanmingguan;
use App\Models\LaporanAkhir;

class DashboardDosenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosenPembimbing;

        if (!$dosen) {
            abort(404, 'Data dosen pembimbing tidak ditemukan');
        }

        // Cek apakah password sudah pernah diganti
        $isDefaultPassword = !$user->is_password_changed;

        // =========================
        // CARD DATA
        // =========================

        // Jumlah mahasiswa bimbingan
        $jumlahMahasiswa = PengajuanMagang::where('id_dosen', $dosen->id_dosen)
            ->whereIn('status', ['disetujui', 'berjalan'])
            ->count();

        // laporan mingguan (pending)
        $laporanmingguan = laporanmingguan::whereHas('pengajuan', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id_dosen);
            })
            ->where('status', 'pending')
            ->count();

        // Laporan akhir (pending)
        $laporanAkhir = LaporanAkhir::whereHas('pengajuan', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id_dosen);
            })
            ->where('status', 'pending')
            ->count();

        // =========================
        // AKTIVITAS TERBARU
        // =========================

        $aktivitasTerbaru = collect()

            // laporan mingguan
            ->merge(
                laporanmingguan::with(['pengajuan.mahasiswa'])
                    ->whereHas('pengajuan', function ($q) use ($dosen) {
                        $q->where('id_dosen', $dosen->id_dosen);
                    })
                    ->orderByDesc('tanggal_upload')
                    ->limit(3)
                    ->get()
                    ->map(function ($laporan) {
                        return [
                            'nama' => $laporan->pengajuan->mahasiswa->nama_lengkap ?? '-',
                            'npm' => $laporan->pengajuan->mahasiswa->npm ?? '-',
                            'catatan' => 'Mengunggah laporan mingguan',
                            'waktu' => $laporan->tanggal_upload?->diffForHumans(),
                        ];
                    })
            )

            // Laporan Akhir
            ->merge(
                LaporanAkhir::with(['pengajuan.mahasiswa'])
                    ->whereHas('pengajuan', function ($q) use ($dosen) {
                        $q->where('id_dosen', $dosen->id_dosen);
                    })
                    ->orderByDesc('tanggal_upload')
                    ->limit(3)
                    ->get()
                    ->map(function ($laporan) {
                        return [
                            'nama' => $laporan->pengajuan->mahasiswa->nama_lengkap ?? '-',
                            'npm' => $laporan->pengajuan->mahasiswa->npm ?? '-',
                            'catatan' => 'Mengunggah laporan akhir',
                            'waktu' => $laporan->tanggal_upload?->diffForHumans(),
                        ];
                    })
            )

            ->sortByDesc('waktu')
            ->take(5)
            ->values();

        return view(
            'dashboard.dosen-pembimbing.dashboard_utama_dosen-pembimbing',
            compact(
                'jumlahMahasiswa',
                'laporanmingguan',
                'laporanAkhir',
                'aktivitasTerbaru',
                'isDefaultPassword'
            )
        );
    }
}