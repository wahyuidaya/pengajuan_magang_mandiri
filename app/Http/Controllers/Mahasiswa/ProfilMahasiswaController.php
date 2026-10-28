<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller; // ✅ WAJIB
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilMahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('dashboard.mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        return view('dashboard.mahasiswa.profil_mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'user' => $user
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'npm' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'npm')->ignore($mahasiswa->id_mhs, 'id_mhs')
            ],
            'no_hp' => 'required|string|max:20',
            'prodi' => 'required|string|max:100',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ];

        // 👉 VALIDASI EMAIL HANYA JIKA BERUBAH
        if ($request->email !== $user->email) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')
            ];
        }

        $validated = $request->validate($rules);


        \DB::transaction(function () use ($validated, $user, $mahasiswa, $request) {

            $mahasiswa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'npm' => $validated['npm'],
                'no_hp' => $validated['no_hp'], // ✅ SEKARANG PASTI UPDATE
                'prodi' => $validated['prodi'],
                'ipk' => $validated['ipk'],
                'email' => $request->email,
            ]);

            // Update users.email HANYA JIKA BERUBAH
            if ($request->email !== $user->email) {
                $user->update([
                    'email' => $request->email
                ]);
            }
        });


        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
        ]);
    }


    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $mahasiswa = Auth::user()->mahasiswa;

        if ($mahasiswa->foto_url) {
            Storage::disk('public')->delete($mahasiswa->foto_url);
        }

        $path = $request->file('foto')
            ->store('foto_mahasiswa', 'public');

        $mahasiswa->update([
            'foto_url' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diperbarui',
            'foto_url' => Storage::url($path)
        ]);
    }

    public function deleteFoto()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if ($mahasiswa->foto_url) {
            Storage::disk('public')->delete($mahasiswa->foto_url);
        }

        $mahasiswa->update(['foto_url' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}