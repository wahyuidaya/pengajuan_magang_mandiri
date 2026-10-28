<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\PenilaianMagang;
use App\Models\User;
use App\Models\MitraPerusahaan;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\StafProdi\ActivityLogController;

class MitraController extends Controller
{
    /**
     * Tampilkan halaman data mitra dan penilaian
     */
    public function index(Request $request)
    {
        $query = PengajuanMagang::with(['mahasiswa', 'mitra', 'penilaian'])
            ->where('status', 'disetujui');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($qm) use ($search) {
                        $qm->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('npm', 'like', "%{$search}%");
                    })
                  ->orWhereHas('mitra', function ($qm) use ($search) {
                        $qm->where('nama_perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->get('per_page', 10);
        $dataList = $query->orderByDesc('tanggal_pengajuan')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $mitraBelumVerifikasi = MitraPerusahaan::whereNull('id_user')
            ->orWhere('status_verifikasi', '!=', 'disetujui')
            ->get();

        return view('dashboard.staf-prodi.data_mitra_dan_penilaian', compact('dataList', 'mitraBelumVerifikasi'));
    }

    /**
     * Get detail penilaian mahasiswa (AJAX)
     */
    public function getDetail($id_pengajuan)
    {
        try {
            $pengajuan = PengajuanMagang::with(['mahasiswa', 'mitra', 'penilaian'])
                ->findOrFail($id_pengajuan);

            $penilaian = $pengajuan->penilaian;

            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $pengajuan->mahasiswa->nama_lengkap ?? '-',
                    'npm' => $pengajuan->mahasiswa->npm ?? '-',
                    'mitra' => $pengajuan->mitra->nama_perusahaan ?? '-',
                    'periode' => $pengajuan->periode_magang ?? '-',
                    'posisi' => $pengajuan->posisi_dilamar ?? '-',
                    'file_penilaian' => $penilaian?->file_penilaian ? asset('storage/' . $penilaian->file_penilaian) : null,
                    'tanggal_upload' => $penilaian?->tanggal_upload_mitra ? date('d M Y', strtotime($penilaian->tanggal_upload_mitra)) : '-',
                    'nilai_angka' => $pengajuan->nilaiAkhir?->nilai_angka !== null ? (int) $pengajuan->nilaiAkhir->nilai_angka : null,
                    'nilai_akhir' => $pengajuan->nilaiAkhir?->nilai_huruf ?? '-',
                    'status' => $penilaian && $penilaian->terisi ? 'terisi' : '-',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

/**
     * Update nilai akhir oleh staf prodi
     */
    public function updateNilai(Request $request, $id_pengajuan)
    {
        try {
            \Log::info('Update Nilai Request', [
                'id_pengajuan' => $id_pengajuan,
                'data' => $request->all()
            ]);

            $request->validate([
                'nilai_huruf' => 'required|string|max:3|in:A,B,C,D,E',
                'nilai_angka' => 'required|integer|min:0|max:100',
            ]);

            // Ambil relasi penilaian untuk mengecek file
            $pengajuan = PengajuanMagang::with(['dosenPembimbing', 'penilaian'])->findOrFail($id_pengajuan);

            // 1. Validasi Keberadaan Dosen Pembimbing
            if (!$pengajuan->dosenPembimbing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosen pembimbing belum tersedia'
                ], 422);
            }

            // 2. Validasi File Penilaian Mitra (Pencegahan input jika file belum ada)
            if (!$pengajuan->penilaian || !$pengajuan->penilaian->file_penilaian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai akhir tidak dapat disimpan karena mitra belum mengunggah file penilaian.'
                ], 422);
            }

            // 3. Simpan atau Update Nilai Akhir
            $nilai = NilaiAkhir::updateOrCreate(
                ['id_pengajuan' => $id_pengajuan],
                [
                    // Gunakan ID yang sudah ada jika update, atau buat baru jika create
                    'id_nilai' => (string) Str::uuid(), 
                    'id_dosen' => $pengajuan->dosenPembimbing->id_dosen,
                    'nilai_huruf' => strtoupper($request->nilai_huruf),
                    'nilai_angka' => (int) $request->nilai_angka,
                    'periode' => $pengajuan->periode_magang ?? '-', // Solusi agar tidak NULL
                    'tanggal_penetapan' => now(),
                ]
            );

            ActivityLogController::saveLog(
                'Update Nilai Akhir (Mitra)', 
                "Input Nilai Angka: {$request->nilai_angka} dan Huruf: {$request->nilai_huruf} untuk Pengajuan ID: {$id_pengajuan}"
            );

            return response()->json([
                'success' => true,
                'nilai_huruf' => $nilai->nilai_huruf,
                'nilai_angka' => (int) $nilai->nilai_angka,
                'message' => 'Nilai akhir berhasil disimpan'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error Update Nilai', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file penilaian dari mitra
     */
    public function downloadFilePenilaian($id_pengajuan)
    {
        try {
            $penilaian = PenilaianMagang::where('id_pengajuan', $id_pengajuan)->firstOrFail();

            if (!$penilaian->file_penilaian) {
                return back()->with('error', 'File penilaian tidak ditemukan');
            }

            $filePath = storage_path('app/' . $penilaian->file_penilaian);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan di server');
            }

            ActivityLogController::saveLog(
                'Download Penilaian Mitra', 
                "Mendownload file penilaian mitra untuk Pengajuan ID: {$id_pengajuan}"
            );

            return response()->download($filePath);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Tambah Mitra Perusahaan
     */
public function tambahMitra(Request $request)
{
    // 1. Validasi: Cek keunikan email di kedua tabel agar tidak terjadi error database
    $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'email_pic' => 'required|email|unique:users,email|unique:mitra_perusahaan,email_pic',
    ], [
        'email_pic.unique' => 'Email ini sudah terdaftar sebagai akun lain.',
    ]);

    try {
        DB::transaction(function() use ($request) {
            $passwordDefault = 'password123';
            $id_user = (string) Str::uuid();

            // 2. Simpan ke tabel users
            // Atribut 'email' diisi sesuai permintaan Anda
            $user = User::create([
                'id_user' => $id_user,
                'username' => $request->email_pic, // Username menggunakan email
                'email' => $request->email_pic,    // Email juga disimpan di tabel users
                'password_hash' => bcrypt($passwordDefault),
                'role' => 'mitra_perusahaan',
                'force_change_password' => 1,
            ]);

            // 3. Simpan ke tabel mitra_perusahaan
            // Atribut 'nama_perusahaan' dan 'email_pic' diisi sesuai inputan
            MitraPerusahaan::create([
                'id_mitra' => (string) Str::uuid(),
                'id_user' => $id_user,
                'nama_perusahaan' => $request->nama_perusahaan,
                'email_pic' => $request->email_pic,
                'status_verifikasi' => 'disetujui',
            ]);

            // 4. Pengiriman Email Informasi Login (Disesuaikan)
            $pesan = "Yth. PIC {$request->nama_perusahaan},\n\n";
            $pesan .= "Selamat, akun Mitra Perusahaan Anda pada Sistem Informasi Magang telah berhasil dibuat.\n\n";
            $pesan .= "Berikut adalah rincian informasi login Anda:\n";
            $pesan .= "--------------------------------------------------\n";
            $pesan .= "Email : {$request->email_pic}\n";
            $pesan .= "Password Default : {$passwordDefault}\n";
            $pesan .= "--------------------------------------------------\n\n";
            $pesan .= "Tautan Login: " . url('/login') . "\n\n";
            $pesan .= "Demi keamanan, mohon segera ubah password default Anda pada menu profil setelah berhasil masuk ke sistem.\n\n";
            $pesan .= "Salam,\nTim Administrator Sistem Informasi Magang";

            Mail::raw($pesan, function ($message) use ($request) {
                $message->to($request->email_pic)
                    ->subject('Akses Login Mitra Perusahaan - ' . $request->nama_perusahaan);
            });

            ActivityLogController::saveLog(
                'Tambah Mitra Baru', 
                "Membuat akun mitra: {$request->nama_perusahaan} dengan email: {$request->email_pic}"
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Mitra berhasil ditambahkan. Email informasi login telah dikirim.'
        ]);

    } catch (\Exception $e) {
        \Log::error('Tambah Mitra Error', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan mitra: ' . $e->getMessage()
        ], 500);
    }
}
}
