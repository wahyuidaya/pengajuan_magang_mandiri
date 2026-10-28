<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StafProdi\ActivityLogController;

class DataAkunController extends Controller
{
public function index(Request $request)
{
    // Ambil query dasar, sembunyikan staf_prodi agar staf tidak menghapus akunnya sendiri
    $query = User::with([
        'mahasiswa',
        'dosenPembimbing',
        'mitra',
    ])->where('role', '!=', 'staf_prodi');

    // 1. Filter Role (Pastikan value sesuai dengan isi kolom role di database)
    if ($request->filled('role')) {
        $role = $request->role;
        
        // Mapping otomatis jika input dari form berbeda dengan database
        // Tapi lebih baik value di HTML-nya langsung yang disesuaikan (Lihat bagian Blade)
        $query->where('role', $role);
    }

    // 2. Search Logic (Sudah diperbaiki agar lebih rapi)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            // Cek di tabel users langsung (username/email)
            $q->where('username', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              
            // Cek di tabel mahasiswa
            ->orWhereHas('mahasiswa', function($q2) use ($search) {
                $q2->where('nama_lengkap', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            })
            
            // Cek di tabel dosen
            ->orWhereHas('dosenPembimbing', function($q2) use ($search) {
                $q2->where('nama_dosen', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            })
            
            // Cek di tabel mitra
            ->orWhereHas('mitra', function($q2) use ($search) {
                $q2->where('nama_perusahaan', 'like', "%{$search}%")
                   ->orWhere('email_pic', 'like', "%{$search}%");
            });
        });
    }

    $users = $query->orderBy('id_user', 'desc')
                   ->paginate(10)
                   ->withQueryString(); // Menjaga filter tetap ada saat ganti halaman

    return view('dashboard.staf-prodi.data_akun', compact('users'));
}


public function destroy($id)
{
    DB::beginTransaction();
    try {
        // 1. Ambil data user beserta relasinya sebelum dihapus
        $user = User::with(['mahasiswa', 'dosenPembimbing', 'mitra'])->findOrFail($id);

        $emailTujuan = null;
        $namaPenerima = 'Pengguna';
        $roleInfo = $user->role;

        // 2. Identifikasi Email dan Nama berdasarkan Role (untuk Email & Log)
        if ($user->role === 'mahasiswa' && $user->mahasiswa) {
            $emailTujuan = $user->mahasiswa->email;
            $namaPenerima = $user->mahasiswa->nama_lengkap;
            $user->mahasiswa->delete();
        } elseif (($user->role === 'dosen' || $user->role === 'dosen_pembimbing') && $user->dosenPembimbing) {
            $emailTujuan = $user->dosenPembimbing->email;
            $namaPenerima = $user->dosenPembimbing->nama_dosen;
            $user->dosenPembimbing->delete();
        } elseif (($user->role === 'mitra' || $user->role === 'mitra_perusahaan') && $user->mitra) {
            $emailTujuan = $user->mitra->email_pic;
            $namaPenerima = $user->mitra->nama_perusahaan;
            $user->mitra->delete();
        } else {
            $emailTujuan = $user->email ?? $user->username;
        }

        ActivityLogController::saveLog(
            'Hapus Akun', 
            "Nama: {$namaPenerima} | Role: {$roleInfo} | Email/ID: {$emailTujuan}"
        );

        // 3. Hapus user utama
        $user->delete();

        // 4. Kirim email pemberitahuan
        if ($emailTujuan && filter_var($emailTujuan, FILTER_VALIDATE_EMAIL)) {
            $pesan = "Yth. {$namaPenerima},\n\n";
            $pesan .= "Kami menginformasikan bahwa akun Anda pada Sistem Informasi Magang telah resmi dihapus/dinonaktifkan oleh pihak Administrator.\n\n";
            $pesan .= "Sehubungan dengan hal tersebut, seluruh akses Anda ke dalam sistem telah ditutup. Jika Anda merasa ini adalah sebuah kekeliruan atau memerlukan informasi lebih lanjut, silakan hubungi Staf Program Studi terkait.\n\n";
            $pesan .= "Terima kasih atas partisipasi dan kerja sama Anda selama ini.\n\n";
            $pesan .= "Salam,\nTim Administrator Sistem Informasi Magang";

            \Mail::raw($pesan, function ($message) use ($emailTujuan) {
                $message->to($emailTujuan)
                    ->subject('Informasi Penonaktifan Akun - Sistem Informasi Magang');
            });
        }

        DB::commit();
        return redirect()->back()->with('success', 'Akun berhasil dihapus. Jejak aktivitas telah dicatat.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Gagal menghapus akun: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
    }
}
}
