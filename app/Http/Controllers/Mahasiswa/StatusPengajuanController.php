<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StatusPengajuanController extends Controller
{
    /* =====================================================
     * HALAMAN STATUS PENGAJUAN
     * ===================================================== */
    public function index()
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();

        if (!$mahasiswa) {
            return view('dashboard.mahasiswa.status_pengajuan', [
                'pengajuanList' => collect(),
                'mahasiswa' => null
            ]);
        }

        $pengajuanList = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('dashboard.mahasiswa.status_pengajuan', compact('pengajuanList', 'mahasiswa'));
    }

    /* =====================================================
     * UPLOAD SILABUS
     * ===================================================== */
    public function uploadSilabus(Request $request)
    {
        $request->validate([
            'file_silabus' => 'required|mimes:pdf,docx|max:5120',
        ]);

        $pengajuan = PengajuanMagang::findOrFail($request->id_pengajuan);

        // upload file
        $path = $request->file('file_silabus')->store('silabus', 'public');
        $pengajuan->silabus_url = $path;
        $pengajuan->save();

        // cek apakah update atau upload pertama
        if ($request->is_update == 1) {
            return redirect()->back()->with('success', 'Silabus berhasil diperbarui');
        }

        return redirect()->back()->with('success', 'Silabus berhasil diunggah');
    }

    /* =====================================================
     * DOWNLOAD SILABUS
     * ===================================================== */
    public function downloadSilabus($id_pengajuan)
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::id())->firstOrFail();

        $pengajuan = PengajuanMagang::where('id_pengajuan', $id_pengajuan)
            ->where('id_mhs', $mahasiswa->id_mhs)
            ->firstOrFail();

        return response()->download(
            storage_path('app/public/' . $pengajuan->silabus_url)
        );
    }
}
