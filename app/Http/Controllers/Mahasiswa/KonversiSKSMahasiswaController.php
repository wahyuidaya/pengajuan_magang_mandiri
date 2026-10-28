<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class KonversiSKSMahasiswaController extends Controller
{
public function index()
{
    $user = Auth::user();
    $mahasiswa = $user->mahasiswa;

    if (!$mahasiswa) {
        abort(404, 'Data mahasiswa tidak ditemukan');
    }

    $pengajuanList = PengajuanMagang::with([
            'mitra',
            'konversiSks'
        ])
        ->whereHas('mahasiswa', function ($q) use ($mahasiswa) {
            $q->where('id_user', $mahasiswa->id_user);
        })
        ->get();

    return view('dashboard.mahasiswa.konversi_sks_saya', compact(
        'pengajuanList',
        'mahasiswa'
    ));
}

public function download($id_pengajuan)
{
    $pengajuan = PengajuanMagang::with('konversiSks')->findOrFail($id_pengajuan);

    // ambil file dari table konversi_sks
    $file = $pengajuan->konversiSks->first();

    if (!$file || !$file->path_file) {
        abort(404, 'File konversi SKS tidak tersedia');
    }

    $fullPath = storage_path('app/public/' . $file->path_file);

    if (!file_exists($fullPath)) {
        abort(404, 'File tidak ditemukan di storage');
    }

    return response()->download(
        $fullPath,
        $file->nama_file
    );
}

}
