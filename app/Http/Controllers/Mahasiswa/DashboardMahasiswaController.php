<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\LogStatusPengajuan;

class DashboardMahasiswaController extends Controller
{
    public function index()
    {
        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();

        // Ambil pengajuan magang terakhir
        $pengajuan = $mahasiswa
            ? PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->with(['dosenPembimbing'])
                ->latest('tanggal_pengajuan')
                ->first()
            : null;

        // Ambil 5 aktivitas terbaru (log status)
        $aktivitasTerbaru = $pengajuan
            ? LogStatusPengajuan::with('user')
                ->where('id_pengajuan', $pengajuan->id_pengajuan)
                ->orderBy('timestamp', 'desc')
                ->limit(5)
                ->get()
            : collect([]);

        return view('dashboard.mahasiswa.dashboard_utama_mahasiswa', compact(
            'mahasiswa',
            'pengajuan',
            'aktivitasTerbaru'
        ));


    }
}
