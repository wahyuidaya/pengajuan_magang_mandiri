<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\StafProdi\ActivityLogController;


class DosenPembimbingController extends Controller
{
    /**
     * Tampilkan halaman data dosen pembimbing
     */
public function index(Request $request)
{
    try {
        // Tangkap keyword search
        $search = $request->input('search');

        // Query dasar
        $query = DosenPembimbing::query();

        // Jika ada search, filter nama_dosen atau email
        if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nama_dosen', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                    });
                }

        // Order dan paginate
        $dosenList = $query->orderBy('nama_dosen', 'asc')->paginate(10);

        // Biar search tetap tersimpan di pagination links
        $dosenList->appends($request->all());

        // Hitung jumlah mahasiswa untuk masing-masing dosen
        foreach ($dosenList as $dosen) {
            $dosen->jumlah_mahasiswa = PengajuanMagang::where('id_dosen', $dosen->id_dosen)
                ->where('status', 'disetujui')
                ->count();
        }

        return view('dashboard.staf-prodi.data_dosen_pembimbing', compact('dosenList'));

    } catch (\Exception $e) {
        return view('dashboard.staf-prodi.data_dosen_pembimbing', [
            'dosenList' => collect([]),
            'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
        ]);
    }
}



    /**
     * Get detail dosen dan mahasiswa bimbingannya (AJAX)
     */
    public function getDetail($id_dosen)
    {
        try {
            $dosen = DosenPembimbing::with([
                'pengajuanMagang' => function($query) {
                    $query->where('status', 'disetujui')
                          ->with(['mahasiswa', 'mitra'])
                          ->orderBy('tanggal_pengajuan', 'desc');
                }
            ])->findOrFail($id_dosen);

            // Format data mahasiswa bimbingan
            $mahasiswaList = $dosen->pengajuanMagang->map(function($pengajuan) {
                return [
                    'nama' => $pengajuan->mahasiswa->nama_lengkap ?? '-',
                    'npm' => $pengajuan->mahasiswa->npm ?? '-',
                    'perusahaan' => $pengajuan->mitra->nama_perusahaan ?? '-',
                    'status' => ucfirst($pengajuan->status),
                    'tanggal_mulai' => $pengajuan->tanggal_mulai ? date('d M Y', strtotime($pengajuan->tanggal_mulai)) : '-',
                    'tanggal_selesai' => $pengajuan->tanggal_selesai ? date('d M Y', strtotime($pengajuan->tanggal_selesai)) : '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $dosen->nama_dosen,
                    'email' => $dosen->email,
                    'jumlah_mahasiswa' => $mahasiswaList->count(),
                    'mahasiswa_list' => $mahasiswaList
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store dosen baru (untuk tombol Tambah Dosen)
     */

public function store(Request $request)
{
    try {
        // 1. Validasi input: Cek keunikan di tabel dosen dan tabel users sekaligus
        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:dosen_pembimbing,email|unique:users,email', // Ubah 50 ke 255
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $id_dosen = (string) \Illuminate\Support\Str::uuid();
        $id_user = (string) \Illuminate\Support\Str::uuid();
        $username = $validated['email'];
        $passwordDefault = 'password123';

        // 2. Gunakan Transaction untuk memastikan kedua tabel terisi dengan aman
        DB::transaction(function () use ($validated, $id_dosen, $id_user, $username, $passwordDefault) {
            
            // Simpan ke tabel users (Atribut: username (Email) dan email)
            \App\Models\User::create([
                'id_user' => $id_user,
                'username' => $username,
                'email' => $validated['email'], // Simpan email juga di users
                'password_hash' => bcrypt($passwordDefault),
                'role' => 'dosen_pembimbing',
            ]);

            // Simpan ke tabel dosen_pembimbing (Atribut: nama_dosen, email)
            DosenPembimbing::create([
                'id_dosen' => $id_dosen,
                'id_user' => $id_user,
                'nama_dosen' => $validated['nama_dosen'], 
                'email' => $validated['email'], 
            ]);
        });

        // 3. Pengiriman Email Informasi Login (Disesuaikan agar lebih jelas)
        $pesan = "Yth. Bapak/Ibu {$validated['nama_dosen']},\n\n";
        $pesan .= "Selamat, akun Anda sebagai Dosen Pembimbing pada Sistem Informasi Magang telah berhasil dibuat.\n\n";
        $pesan .= "Berikut adalah rincian informasi login Anda:\n";
        $pesan .= "--------------------------------------------------\n";
        $pesan .= "Email Terdaftar : {$validated['email']}\n";
        $pesan .= "Password Default: {$passwordDefault}\n";
        $pesan .= "--------------------------------------------------\n\n";
        $pesan .= "Silakan login melalui tautan berikut: " . url('/login') . "\n\n";
        $pesan .= "PENTING: Demi keamanan akun, mohon segera ubah password default Anda setelah berhasil masuk ke sistem.\n\n";
        $pesan .= "Salam,\nTim Administrator Sistem Informasi Magang";

        Mail::raw($pesan, function ($message) use ($validated) {
            $message->to($validated['email'])
                ->subject('Akses Akun Dosen Pembimbing - Sistem Informasi Magang');
        });

        ActivityLogController::saveLog(
            'Tambah Dosen Pembimbing', 
            "Nama: {$validated['nama_dosen']} | Email: {$validated['email']}"
        );
        return redirect()->route('staf-prodi.data_dosen_pembimbing')
            ->with('success', 'Dosen pembimbing berhasil ditambahkan! Email informasi login telah dikirim.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->with('error', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()))->withInput();
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menambahkan dosen: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Update data dosen
     */
    public function update(Request $request, $id_dosen)
    {
        try {
            $dosen = DosenPembimbing::findOrFail($id_dosen);

            $validated = $request->validate([
                'nama_dosen' => 'required|string|max:255',
                'email' => 'required|email|max:50|unique:dosen_pembimbing,email,' . $id_dosen . ',id_dosen',
            ]);

            $dosen->update($validated);
            ActivityLogController::saveLog(
                'Update Data Dosen', 
                "Dosen ID: {$id_dosen} diubah menjadi Nama: {$validated['nama_dosen']} | Email: {$validated['email']}"
            );

            // TAMBAHKAN INI:
            \App\Models\User::where('id_user', $dosen->id_user)->update([
                'username' => $validated['email'],
                'email' => $validated['email']
            ]);

            return redirect()->route('staf-prodi.data_dosen_pembimbing')
                ->with('success', 'Data dosen berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Delete dosen
     */
    public function destroy($id_dosen)
    {
        try {
            $dosen = DosenPembimbing::findOrFail($id_dosen);
            ActivityLogController::saveLog(
                'Hapus Dosen Pembimbing', 
                "Nama: {$dosen->nama_dosen} | Email: {$dosen->email}"
            );

            // Cek apakah dosen masih punya mahasiswa bimbingan aktif
            $jumlahMahasiswa = PengajuanMagang::where('id_dosen', $id_dosen)
                ->where('status', 'disetujui')
                ->count();

            if ($jumlahMahasiswa > 0) {
                return back()->with('error', 'Tidak dapat menghapus dosen yang masih memiliki mahasiswa bimbingan aktif!');
            }

            \App\Models\User::where('id_user', $dosen->id_user)->delete();
            
            $dosen->delete();

            return redirect()->route('staf-prodi.data_dosen_pembimbing')
                ->with('success', 'Dosen berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dosen: ' . $e->getMessage());
        }
    }
}