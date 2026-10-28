<?php

namespace App\Http\Controllers\MitraPerusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanMagang;
use App\Models\PenilaianMagang;

class DashboardMitraController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mitra = $user->mitra;

        if (!$mitra) {
            abort(404, 'Data mitra tidak ditemukan');
        }

        // Cek apakah password sudah pernah diganti
        $isDefaultPassword = !$user->is_password_changed;

        // Total mahasiswa yang pernah magang di mitra ini (status disetujui)
        $totalMahasiswa = PengajuanMagang::where('id_mitra', $mitra->id_mitra)
            ->where('status', 'disetujui')
            ->count();

        // Mahasiswa yang sedang aktif magang
        $mahasiswaAktif = PengajuanMagang::where('id_mitra', $mitra->id_mitra)
            ->whereIn('status', ['disetujui', 'berjalan'])
            ->count();

        // Aktivitas terbaru: 3 mahasiswa terakhir yang sudah dinilai
        $aktivitasTerbaru = PengajuanMagang::with(['mahasiswa', 'penilaian'])
            ->where('id_mitra', $mitra->id_mitra)
            ->where('status', 'disetujui')
            ->whereHas('penilaian', function($q) {
                $q->whereNotNull('file_penilaian');
            })
            ->orderByDesc('tanggal_pengajuan')
            ->limit(3)
            ->get()
            ->map(function($pengajuan) {
                return [
                    'nama' => $pengajuan->mahasiswa->nama_lengkap ?? '-',
                    'npm' => $pengajuan->mahasiswa->npm ?? '-',
                    'catatan' => $pengajuan->penilaian->catatan ?? 'Penilaian telah diberikan',
                    'periode' => $pengajuan->periode_magang ?? '-',
                ];
            });

        return view('dashboard.mitra-perusahaan.dashboard_utama_mitra-perusahaan', [
            'totalMahasiswa' => $totalMahasiswa,
            'mahasiswaAktif' => $mahasiswaAktif,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'isDefaultPassword' => $isDefaultPassword,
            'activeMenu' => 'dashboard',
            'title' => 'Dashboard Mitra Perusahaan',
        ]);
    }
}