<?php

namespace App\Http\Controllers\MitraPerusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilMitraController extends Controller
{
    /**
     * Tampilkan halaman profil mitra
     */
    public function index()
    {
        $user = Auth::user();

        // relasi dari User -> mitra()
        $mitra = $user->mitra;

        if (!$mitra) {
            abort(404, 'Data mitra tidak ditemukan');
        }

        return view('dashboard.mitra-perusahaan.profil_mitra', [
            'mitra' => $mitra,
            'activeMenu' => 'profil_mitra',
            'title' => 'Profil Perusahaan',
        ]);
    }

    /**
     * Update data mitra (PIC & alamat)
     */
    public function updateData(Request $request)
    {
        $request->validate([
            'no_telp_pic' => 'required|string|max:20',
            'email_pic'   => 'required|email',
            'alamat'      => 'nullable|string',
        ]);

        $mitra = Auth::user()->mitra;

        $mitra->update([
            'no_telp_pic' => $request->no_telp_pic,
            'email_pic'   => $request->email_pic,
            'alamat'      => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Data mitra berhasil diperbarui',
        ]);
    }

    /**
     * Update password akun mitra
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai',
            ], 422);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password),
            'is_password_changed' => true, // ✅ INI KUNCINYA
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui',
        ]);
    }

}
