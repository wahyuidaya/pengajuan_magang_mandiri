<?php

namespace App\Http\Controllers\DosenPembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilDosenController extends Controller
{
    /**
     * Tampilkan halaman profil dosen
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosenPembimbing;

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan');
        }

        return view('dashboard.dosen-pembimbing.profil_dosen', [
            'dosen' => $dosen,
            'activeMenu' => 'profil_dosen',
            'title' => 'Profil Dosen',
        ]);
    }

    /**
     * Update email dosen (nama readonly)
     */
    public function updateBiodata(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150',
        ]);

        $dosen = Auth::user()->dosenPembimbing;

        if (!$dosen) {
            return response()->json([
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $dosen->update([
            'email' => $request->email,
        ]);

        $user = Auth::user();
        $user->update([
            'email' => $request->email,
            'username' => $request->email
        ]);

        return response()->json([
            'message' => 'Profil dosen berhasil diperbarui',
        ]);
    }

    /**
     * Update password akun dosen
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

        // Update password dan flag
        $user->password_hash = Hash::make($request->new_password);
        $user->is_password_changed = 1; // TAMBAHKAN INI
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diubah',
        ]);
    }
}