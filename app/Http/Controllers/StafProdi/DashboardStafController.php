<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;

class DashboardStafController extends Controller
{
public function index()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();

        // Cek apakah password sudah pernah diganti (logic flag is_password_changed)
        $isDefaultPassword = !$user->is_password_changed;

        // CARD DATA (Tetap dipertahankan)
        $pengajuanAktif = PengajuanMagang::whereIn('status', ['diajukan', 'verifikasi', 'berjalan'])->count();
        $pengajuanDisetujui = PengajuanMagang::where('status', 'disetujui')->count();
        $pengajuanDitolak = PengajuanMagang::where('status', 'ditolak')->count();
        $totalMahasiswa = PengajuanMagang::distinct('id_mhs')->count('id_mhs');

        // AKTIVITAS TERBARU (ambil 5 terbaru - Tetap dipertahankan)
        $aktivitasTerbaru = PengajuanMagang::with('mahasiswa')
            ->orderByDesc('tanggal_pengajuan')
            ->limit(5)
            ->get()
            ->map(function ($pengajuan) {
                return [
                    'nama' => $pengajuan->mahasiswa->nama_lengkap ?? '-',
                    'npm' => $pengajuan->mahasiswa->npm ?? '-',
                    'catatan' => "Pengajuan status: " . ucfirst($pengajuan->status),
                    'waktu' => $pengajuan->tanggal_pengajuan?->diffForHumans(),
                ];
            });

        // Mengirimkan semua data termasuk 'isDefaultPassword' ke view
        return view('dashboard.staf-prodi.dashboard_utama_staf-prodi', compact(
            'pengajuanAktif',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'totalMahasiswa',
            'aktivitasTerbaru',
            'isDefaultPassword'
        ));
    }
}
