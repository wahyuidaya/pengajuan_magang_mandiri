<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilStafController extends Controller
{
    /**
     * Update Email Staf Prodi
     */
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'email',
                // Penyederhanaan Rule::unique sesuai saran editor
                Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'),
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan oleh pengguna lain.',
        ]);

        $user->update([
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Email berhasil diperbarui',
        ]);
    }

    /**
     * Update Password Staf Prodi
     */
    public function updatePassword(Request $request)
        {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min:8|confirmed',
            ], [
                'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
                'new_password.min'       => 'Password minimal harus 8 karakter.',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password_hash)) {
                return response()->json([
                    'message' => 'Password lama tidak sesuai',
                ], 422);
            }

            // UPDATE DI SINI: Tambahkan is_password_changed => 1
            $user->update([
                'password_hash' => Hash::make($request->new_password),
                'is_password_changed' => 1, 
            ]);

            return response()->json([
                'message' => 'Password berhasil diperbarui',
            ]);
        }
}