<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\StafProdi\ActivityLogController;

class StafManagementController extends Controller
{
    public function index() {
        $staf_list = User::where('role', 'staf_prodi')->orderBy('created_at', 'desc')->get();
        return view('dashboard.staf-prodi.manajemen_staf', compact('staf_list'))->with('activeMenu', 'manajemen_staf');
    }

    public function store(Request $request) {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        try {
            User::create([
                'id_user' => (string) Str::uuid(),
                'username' => $request->username,
                'email' => $request->email,
                'password_hash' => Hash::make('password123'),
                'role' => 'staf_prodi',
                'is_password_changed' => false,
            ]);

            ActivityLogController::saveLog('Manajemen Staf', "Mendaftarkan staf baru dengan username: {$request->username}");
            
            return back()->with('success', 'Pendaftaran staf berhasil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendaftarkan staf.');
        }
    }

        public function resetPassword($id) {
                try {
                    // Kita definisikan dulu variabel $user dengan mencari datanya berdasarkan ID
                    $user = User::findOrFail($id); 
                    
                    // Baru kemudian update datanya
                    $user->update([
                        'password_hash' => Hash::make('password123'), 
                        'is_password_changed' => false
                    ]);

                    // Sekarang variabel $user sudah ada dan bisa dipakai di sini
                    ActivityLogController::saveLog('Manajemen Staf', "Mereset password untuk staf: {$user->username}");

                    return back()->with('success', 'Reset password berhasil.');
                } catch (\Exception $e) {
                    return back()->with('error', 'Gagal reset password.');
                }
            }

    public function destroy($id) {
        try {
            $user = User::findOrFail($id);
            if ($user->id_user == auth()->user()->id_user) {
                return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
            }
            ActivityLogController::saveLog('Manajemen Staf', "Menghapus akun staf: {$user->username}");
            $user->delete();
            return back()->with('success', 'Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus akun.');
        }
    }
}