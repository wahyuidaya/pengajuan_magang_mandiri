<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ======================
    // SHOW REGISTER PAGE
    // ======================
    public function showRegister()
    {
        return view('register');
    }

    // ======================
    // SHOW LOGIN PAGE
    // ======================
    public function showLogin()
    {
        return view('login');
    }

    // ======================
    // REGISTER
    // ======================


public function register(Request $request)
{
    // Validasi input tetap dipertahankan
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'npm' => 'required|unique:mahasiswa,npm|unique:users,username', // NPM unik di kedua tabel
        'email' => 'required|email|unique:mahasiswa,email|unique:users,email', // Email unik di kedua tabel
        'nomor_hp' => 'required|digits_between:10,13',
        'password' => 'required|min:6|confirmed',
    ], [
        'npm.unique' => 'NPM ini sudah terdaftar. Silakan cek kembali.',
        'email.unique' => 'Email ini sudah digunakan. Silakan gunakan email lain.',
        'password.confirmed' => 'Konfirmasi password tidak sama.',
    ]);

    // Mengubah variabel username menjadi NPM (sesuai permintaan Anda)
    $username = $request->npm;

    // Pengecekan username (NPM) di tabel users tetap ada untuk keamanan
    if (User::where('username', $username)->exists()) {
        return back()->withErrors([
            'npm' => 'NPM ini sudah digunakan sebagai identitas login.'
        ])->withInput();
    }

    DB::transaction(function () use ($request, $username) {
        $idUser = Str::uuid();
        $idMhs = Str::uuid();

        // Simpan ke tabel users
        User::create([
            'id_user' => $idUser,
            'username' => $username, // Diisi NPM
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        // Simpan ke tabel mahasiswa
        Mahasiswa::create([
            'id_mhs' => $idMhs,
            'id_user' => $idUser,
            'npm' => $request->npm,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->nomor_hp,
            'kelas' => $request->kelas ?? '', 
            'aktif' => true,
        ]);
    });

    return redirect('/login')->with('success', 'Registrasi berhasil, silakan login untuk melanjutkan.');
}


public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
        'user_type' => 'required',
    ]);

    $input = $request->username;
    $userType = $request->user_type;

    // 1. Cari user berdasarkan Email atau Username
    $query = User::where(function($q) use ($input) {
        $q->where('email', $input)
          ->orWhere('username', $input);
    });

    // 2. Tambahan filter spesifik sesuai user_type
    if ($userType === 'mahasiswa') {
        $query->orWhereHas('mahasiswa', function ($q) use ($input) {
            $q->where('npm', $input);
        });
        } elseif ($userType === 'dosen_pembimbing') {
    }

    $user = $query->first();

    // ==========================================
    // VALIDASI DENGAN PESAN GABUNGAN (CUSTOM)
    // ==========================================

    // Jika user tidak ditemukan ATAU password salah
    // Bagian di dalam public function login

    if (!$user || !Hash::check($request->password, $user->password_hash)) {
        
        $errorMessage = match ($userType) {
            'mahasiswa'        => 'Email, NPM, atau Password salah.',
            'dosen_pembimbing' => 'Email atau Password salah.',
            'staf_prodi'       => 'Email, Username, atau Password salah.',
            'mitra_perusahaan' => 'Email atau Password salah.',
            default            => 'Username atau Password salah.'
        };

        return back()->withErrors([
            'username' => $errorMessage
        ])->withInput();
    }

    // Cek Kondisi: Role Tidak Cocok (Jika user ditemukan tapi pilih role yang salah)
    if ($userType !== $user->role) {
        $roleLabel = match ($userType) {
            'mahasiswa'       => 'Mahasiswa',
            'dosen_pembimbing' => 'Dosen Pembimbing',
            'mitra_perusahaan' => 'Mitra Perusahaan',
            'staf_prodi'      => 'Staf Prodi',
            default           => 'Role tersebut'
        };

        return back()->withErrors([
            'username' => "Akun ini tidak terdaftar sebagai $roleLabel."
        ])->withInput();
    }

    // Jika semua lolos, proses login
    Auth::login($user);
    return $this->redirectBasedOnRole($user);
}

    // ======================
    // LOGOUT
    // ======================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    // ======================
    // ROLE REDIRECT
    // ======================
    private function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'mahasiswa' => redirect('/dashboard_utama_mahasiswa'),
            'mitra_perusahaan' => redirect('/dashboard_utama_mitra-perusahaan'),
            'staf_prodi' => redirect('/dashboard_utama_staf-prodi'),
            'dosen_pembimbing' => redirect('/dashboard_utama_dosen-pembimbing'),
            default => redirect('/login'),
        };
    }
}
