<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;    

// =======================================
// CONTROLLER IMPORT
// =======================================
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Mahasiswa\MagangController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\LaporanController;
use App\Http\Controllers\StafProdi\VerifikasiController;
use App\Http\Controllers\StafProdi\FaqController;
use App\Http\Controllers\Mahasiswa\StatusPengajuanController;
use App\Http\Controllers\DosenPembimbing\MahasiswaBimbinganController;
use App\Http\Controllers\DosenPembimbing\LaporanMahasiswaController;
use App\Http\Controllers\StafProdi\MitraController;
use App\Http\Controllers\MitraPerusahaan\DataMahasiswaController;
use App\Http\Controllers\MitraPerusahaan\SilabusController;
use App\Http\Controllers\Mahasiswa\SilabusMitraController;
use App\Http\Controllers\MitraPerusahaan\PenilaianController;
use App\Models\MitraPerusahaan;
use App\Models\Faq;
use App\Http\Controllers\Mahasiswa\ProfilMahasiswaController;
use App\Http\Controllers\MitraPerusahaan\ProfilMitraController;
use App\Http\Controllers\DosenPembimbing\ProfilDosenController;
use App\Http\Controllers\StafProdi\ArsipController;
use App\Http\Controllers\Mahasiswa\RiwayatMagangController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\StafProdi\DataAkunController;
use App\Http\Controllers\MitraPerusahaan\DashboardMitraController;
use App\Http\Controllers\DosenPembimbing\DashboardDosenController;
use App\Http\Controllers\StafProdi\DashboardStafController;
use App\Http\Controllers\Mahasiswa\DashboardMahasiswaController;
use App\Http\Controllers\StafProdi\MasterDataController;
use App\Http\Controllers\StafProdi\ActivityLogController;
use App\Http\Controllers\StafProdi\StafManagementController;


// HALAMAN UTAMA
// ===========================
Route::get('/', function () {
    return view('portal');
});

Route::get('/informasi-kampus', function () {
    $companies = MitraPerusahaan::aktif()->take(8)->get();
    $faqs = Faq::ordered()->get();
    return view('beranda_kampus', compact('companies', 'faqs'));
})->name('kampus.beranda');

// Halaman semua perusahaan
Route::get('/perusahaan', function () {
    $companies = MitraPerusahaan::aktif()->get();
    return view('perusahaan', compact('companies'));
})->name('perusahaan.index');


// ===========================
// AUTH ROUTE
// ===========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ===========================
// LUPA & RESET PASSWORD
// ===========================
// form lupa password
Route::get('/lupa_password', function () {
    return view('lupa_password');
})->name('password.request');

// submit email
Route::post('/lupa_password', [ResetPasswordController::class, 'sendResetLink'])
    ->name('password.email');

// form reset password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// simpan password baru
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->name('password.update');

// TES EMAIL AUTENTIFIKASI
Route::get('/test-email', function () {
    \Mail::raw('Tes email dari Laravel LOCAL berhasil', function ($m) {
        $m->to('emailkamu@gmail.com')->subject('TES EMAIL LOCAL');
    });

    return 'EMAIL TERKIRIM';
});





// ===========================
// DASHBOARD MAHASISWA
// ===========================
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    // ============================================
    // DASHBOARD UTAMA
    // ============================================
    Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
        Route::get('/dashboard_utama_mahasiswa', [DashboardMahasiswaController::class, 'index'])
            ->name('dashboard.mahasiswa');
    });



    // ============================================
    // PENGAJUAN MAGANG
    // ============================================
    Route::get('/ajukan_magang', function () {
        try {
            $mitraList = MitraPerusahaan::where('status_verifikasi', 'disetujui')
                ->orderBy('nama_perusahaan', 'asc')
                ->get();

        } catch (\Exception $e) {
            $mitraList = collect([]);
        }

        try {
            $mahasiswa = \App\Models\Mahasiswa::where('id_user', Auth::id())->first();
        } catch (\Exception $e) {
            $mahasiswa = null;
        }

        return view('dashboard.mahasiswa.ajukan_magang', compact('mitraList', 'mahasiswa'));
    })->name('mahasiswa.ajukan_magang');

    Route::post('/ajukan_magang', [MagangController::class, 'store'])
        ->name('mahasiswa.ajukan_magang.store');

    Route::get('/detail_pengajuan', function () {
        try {
            $mahasiswa = \App\Models\Mahasiswa::where('id_user', Auth::id())->first();

            if ($mahasiswa) {
                $pengajuan = \App\Models\PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                    ->with(['mitra', 'mahasiswa'])
                    ->latest('tanggal_pengajuan')
                    ->first();
            } else {
                $pengajuan = null;
            }
        } catch (\Exception $e) {
            $pengajuan = null;
            $mahasiswa = null;
        }

        return view('dashboard.mahasiswa.detail_pengajuan', compact('pengajuan', 'mahasiswa'));
    })->name('mahasiswa.detail_pengajuan');

    Route::get('/edit_pengajuan/{id}', function ($id) {
        try {
            $mahasiswa = \App\Models\Mahasiswa::where('id_user', Auth::id())->first();

            if (!$mahasiswa) {
                return redirect()->route('dashboard.mahasiswa')
                    ->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            $pengajuan = \App\Models\PengajuanMagang::where('id_pengajuan', $id)
                ->where('id_mhs', $mahasiswa->id_mhs)
                ->whereIn('status', ['draft', 'revisi'])
                ->with(['mitra'])
                ->first();

            if (!$pengajuan) {
                return redirect()->route('dashboard.mahasiswa')
                    ->with('error', 'Pengajuan tidak ditemukan atau tidak dapat diedit.');
            }

            $mitraList = MitraPerusahaan::where('status', 'aktif')
                ->orderBy('nama_perusahaan', 'asc')
                ->get();

            return view('dashboard.mahasiswa.edit_pengajuan', compact('pengajuan', 'mitraList', 'mahasiswa'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard.mahasiswa')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    })->name('mahasiswa.edit_pengajuan');

    Route::put('/update_pengajuan/{id}', [MagangController::class, 'update'])
        ->name('mahasiswa.update_pengajuan');

    Route::delete('/cancel_pengajuan/{id}', [MagangController::class, 'cancel'])
        ->name('mahasiswa.cancel_pengajuan');

    // ============================================
    // STATUS PENGAJUAN
    // ============================================
    Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        // Status Pengajuan
        Route::get('/status_pengajuan', [StatusPengajuanController::class, 'index'])
            ->name('status_pengajuan');

        // Upload Silabus
        Route::post('/status_pengajuan/upload-silabus', [StatusPengajuanController::class, 'uploadSilabus'])
            ->name('status_pengajuan.upload_silabus');

        // Download Silabus
        Route::get('/status_pengajuan/{id}/download-silabus', [StatusPengajuanController::class, 'downloadSilabus'])
            ->name('status_pengajuan.download_silabus');
    });

 // ============================================
// KONVERSI SKS MAHASISWA
// ============================================

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    Route::get(
        '/konversi-sks-saya',
        [\App\Http\Controllers\Mahasiswa\KonversiSKSMahasiswaController::class, 'index']
    )->name('mahasiswa.konversi_sks_saya');

    Route::get(
        '/konversi-sks/{id_pengajuan}/download',
        [\App\Http\Controllers\Mahasiswa\KonversiSKSMahasiswaController::class, 'download']
    )->name('mahasiswa.konversi_sks.download');

});


    // ============================================
    // RIWAYAT MAGANG
    // ============================================

    Route::get('/riwayat_magang', [RiwayatMagangController::class, 'index'])
        ->name('mahasiswa.riwayat_magang');

    Route::get('/riwayat_magang/download/{id}', [RiwayatMagangController::class, 'downloadFilePenilaian'])
        ->name('mahasiswa.riwayat_magang.download');

    Route::get('/riwayat_magang/{id}/detail', [RiwayatMagangController::class, 'detail'])
        ->name('mahasiswa.riwayat_magang.detail');

    Route::get('/riwayat_magang/export', [RiwayatMagangController::class, 'exportExcel'])
        ->name('mahasiswa.riwayat_magang.export');


    // ============================================
    // CETAK SURAT PENGANTAR
    // ============================================
    Route::get('/cetak_surat_pengantar', [\App\Http\Controllers\Mahasiswa\CetakSuratController::class, 'index'])
        ->name('mahasiswa.cetak_surat_pengantar');

    Route::get('/cetak_surat_pengantar/{id}/download_mahasiswa', [\App\Http\Controllers\Mahasiswa\CetakSuratController::class, 'downloadMahasiswa'])
        ->name('mahasiswa.cetak_surat_pengantar.download_mahasiswa');

    Route::get('/cetak_surat_pengantar/{id}/download_prodi', [\App\Http\Controllers\Mahasiswa\CetakSuratController::class, 'downloadProdi'])
        ->name('mahasiswa.cetak_surat_pengantar.download_prodi');


    // ============================================
    // LAPORAN MAGANG
    // ============================================
    Route::middleware(['auth', 'role:mahasiswa'])
        ->prefix('mahasiswa')
        ->name('mahasiswa.')
        ->group(function () {

            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

            // LAPORAN MINGGUAN
            Route::post('/laporan/mingguan/upload', [LaporanController::class, 'uploadMingguan'])
                ->name('laporan.upload-mingguan');

            Route::get('/laporan/mingguan/view/{id}', [LaporanController::class, 'viewMingguan'])
                ->name('laporan.view-mingguan');

            Route::get('/laporan/mingguan/download/{id}', [LaporanController::class, 'downloadMingguan'])
                ->name('laporan.download-mingguan');

            Route::delete('/laporan/mingguan/delete/{id}', [LaporanController::class, 'deleteMingguan'])
                ->name('laporan.delete-mingguan');


            // LAPORAN AKHIR
            Route::post('/laporan/akhir/upload', [LaporanController::class, 'uploadAkhir'])->name('laporan.upload-akhir');
            Route::get('/laporan/akhir/view/{id}', [LaporanController::class, 'viewAkhir'])->name('laporan.view-akhir');
            Route::get('/laporan/akhir/download/{id}', [LaporanController::class, 'downloadAkhir'])->name('laporan.download-akhir');
        });

    // ============================================
    // SILABUS DARI MITRA
    // ============================================
    Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

        Route::prefix('silabus-dari-mitra')
            ->name('mahasiswa.silabus_dari_mitra.')
            ->group(function () {

                Route::get('/', [SilabusMitraController::class, 'index'])
                    ->name('index');

                Route::get('/download/{id_silabus}', [SilabusMitraController::class, 'download'])
                    ->name('download');
            });
    });

    // ============================================
    // PROFIL MAHASISWA
    // ============================================
    Route::prefix('profil_mahasiswa')
        ->name('mahasiswa.profil_mahasiswa.')
        ->middleware(['auth', 'role:mahasiswa'])
        ->group(function () {

            Route::get('/', [ProfilMahasiswaController::class, 'index'])->name('index');
            Route::put('/update', [ProfilMahasiswaController::class, 'updateProfil'])->name('update');
            Route::post('/foto/upload', [ProfilMahasiswaController::class, 'updateFoto'])->name('foto.upload');
            Route::delete('/foto/delete', [ProfilMahasiswaController::class, 'deleteFoto'])->name('foto.delete');
            Route::put('/password', [ProfilMahasiswaController::class, 'updatePassword'])->name('password.update');
        });

});




// ===========================
// DASHBOARD STAF PRODI
// ===========================
Route::get('/reset-staf-password', function () {
    $newPassword = Hash::make('password123');
    
    User::where('role', 'staf_prodi')->update([
        'password_hash' => $newPassword,
        'is_password_changed' => 0
    ]);

    return "Password staf berhasil direset menjadi: password123 (Hash: " . $newPassword . ")";
});

Route::middleware(['auth', 'role:staf_prodi'])->group(function () {
    

    Route::get(
        '/dashboard_utama_staf-prodi',
        [DashboardStafController::class, 'index']
    )->name('dashboard.staf-prodi');


    // ============================================
    // VERIFIKASI PENGAJUAN
    // ============================================
    Route::get('/verifikasi_pengajuan', [VerifikasiController::class, 'index'])
        ->name('staf-prodi.verifikasi_pengajuan');

    Route::get('/verifikasi_pengajuan/{id}', [VerifikasiController::class, 'show'])
        ->name('staf-prodi.verifikasi_pengajuan.detail');

    Route::post('/verifikasi_pengajuan/{id}/approve', [VerifikasiController::class, 'approve'])
        ->name('staf-prodi.verifikasi_pengajuan.approve');

    Route::post('/verifikasi_pengajuan/{id}/reject', [VerifikasiController::class, 'reject'])
        ->name('staf-prodi.verifikasi_pengajuan.reject');

    Route::post('/verifikasi_pengajuan/{id}/revision', [VerifikasiController::class, 'revision'])
        ->name('staf-prodi.verifikasi_pengajuan.revision');

    Route::get('/verifikasi_pengajuan/{id}/view/{jenis}', [VerifikasiController::class, 'viewDokumen'])
        ->name('staf-prodi.verifikasi.view');

    Route::get('/verifikasi_pengajuan/{id}/download/{jenis}', [VerifikasiController::class, 'downloadDokumen'])
        ->name('staf-prodi.verifikasi_pengajuan.download');

    Route::post('/verifikasi/reset/{id}', [VerifikasiController::class, 'resetPengajuan'])->name('staf-prodi.verifikasi_pengajuan.reset');

        

// ============================================
// Konversi SKS Mahasiswa
// ============================================

Route::prefix('staf-prodi/konversi-sks')->group(function () {

    // HALAMAN UTAMA (INI KUNCINYA)
    Route::get('/', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'index'])
        ->name('staf-prodi.konversi_sks');

    // AJAX - Get data untuk modal
    Route::get('/{id_pengajuan}/data', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'getData'])
        ->name('staf-prodi.konversi_sks.get_data');

    // ROUTE UPLOAD FILE KONVERSI (NEW)
    Route::post('/{id_pengajuan}/upload', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'upload'])
        ->name('staf-prodi.konversi_sks.upload');

    Route::post('/store', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'store'])
        ->name('staf-prodi.konversi_sks.store');

    Route::put('/{id_konversi}', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'update'])
        ->name('staf-prodi.konversi_sks.update');

    Route::delete('/{id_konversi}', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'destroy'])
        ->name('staf-prodi.konversi_sks.destroy');

    Route::post('/{id_pengajuan}/approve', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'approve'])
        ->name('staf-prodi.konversi_sks.approve');

    Route::post(
        '/mk/{id_konversi}/approve',
        [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'approveOne']
    )->name('staf-prodi.konversi_sks.approve_one');

    Route::get('/{id_pengajuan}/download-silabus', [\App\Http\Controllers\StafProdi\KonversiSKSController::class, 'downloadSilabus'])
        ->name('staf-prodi.konversi_sks.download_silabus');

});

    // ============================================
// DATA MAHASISWA
// ============================================

    Route::get('/staf_prodi_data_mahasiswa', [MasterDataController::class, 'index'])
        ->name('staf-prodi.staf_prodi_data_mahasiswa');

    Route::get('/data_mahasiswa/{npm}/detail', [MasterDataController::class, 'getDetail'])
        ->name('staf-prodi.data_mahasiswa.detail');

    Route::post('/data_mahasiswa/update_dosen', [MasterDataController::class, 'updateDosenPembimbing'])
        ->name('staf-prodi.data_mahasiswa.update_dosen');

    Route::post('/data_mahasiswa/update_nilai', [MasterDataController::class, 'updateNilaiAkhir'])
        ->name('staf-prodi.data_mahasiswa.update_nilai');

    Route::post('/data_mahasiswa/update_mitra', [MasterDataController::class, 'updateMitra'])
        ->name('staf-prodi.data_mahasiswa.update_mitra');

    Route::get('/data_mahasiswa/{id_pengajuan}/download/{jenis}', [MasterDataController::class, 'downloadDokumen'])
        ->name('staf-prodi.data_mahasiswa.download');

    Route::post('/data_mahasiswa/set_matkul_eligible', [MasterDataController::class, 'setMatkulEligible'])
        ->name('staf-prodi.data_mahasiswa.set_matkul_eligible');

    Route::post('/verifikasi/activate/{id}', [MasterDataController::class, 'activate'])->name('staf-prodi.verifikasi_pengajuan.activate');


    // ============================================
    // DATA DOSEN PEMBIMBING
    // ============================================
    Route::get('/data_dosen_pembimbing', [\App\Http\Controllers\StafProdi\DosenPembimbingController::class, 'index'])
        ->name('staf-prodi.data_dosen_pembimbing');

    Route::get('/data_dosen_pembimbing/{id_dosen}/detail', [\App\Http\Controllers\StafProdi\DosenPembimbingController::class, 'getDetail'])
        ->name('staf-prodi.data_dosen_pembimbing.detail');

    Route::post('/data_dosen_pembimbing/store', [\App\Http\Controllers\StafProdi\DosenPembimbingController::class, 'store'])
        ->name('staf-prodi.data_dosen_pembimbing.store');

    Route::put('/data_dosen_pembimbing/{id_dosen}/update', [\App\Http\Controllers\StafProdi\DosenPembimbingController::class, 'update'])
        ->name('staf-prodi.data_dosen_pembimbing.update');

    Route::delete('/data_dosen_pembimbing/{id_dosen}/delete', [\App\Http\Controllers\StafProdi\DosenPembimbingController::class, 'destroy'])
        ->name('staf-prodi.data_dosen_pembimbing.delete');


    // ============================================
// DATA MITRA DAN PENILAIAN
// ============================================

    Route::get(
        '/data_mitra_dan_penilaian',
        [MitraController::class, 'index']
    )->name('staf-prodi.data_mitra_dan_penilaian');

    Route::get(
        '/data_mitra_dan_penilaian/{id_pengajuan}/detail',
        [MitraController::class, 'getDetail']
    )->name('staf-prodi.data_mitra_dan_penilaian.detail');

    Route::post(
        '/data_mitra_dan_penilaian/{id_pengajuan}/update-nilai',
        [MitraController::class, 'updateNilai']
    )->name('staf-prodi.data_mitra_dan_penilaian.update_nilai');

    Route::get(
        '/data_mitra_dan_penilaian/{id_pengajuan}/download-file',
        [MitraController::class, 'downloadFilePenilaian']
    )->name('staf-prodi.data_mitra_dan_penilaian.download_file');

    Route::post(
        '/mitra/verifikasi',
        [MitraController::class, 'verifikasi']
    )->name('staf-prodi.mitra.verifikasi');

    // === TAMBAH MITRA ===
    Route::post(
        '/mitra/tambah',
        [MitraController::class, 'tambahMitra']
    )->name('staf-prodi.mitra.tambah'); // <== ini yang dibutuhkan Blade





    // ============================================
    // SURAT PENGANTAR
    // ============================================
    Route::get('/surat_pengantar/{id_pengajuan}/download_draft', [\App\Http\Controllers\StafProdi\SuratPengantarController::class, 'downloadDraft'])
        ->name('staf-prodi.surat_pengantar.download_draft');

    Route::get('/surat_pengantar', [\App\Http\Controllers\StafProdi\SuratPengantarController::class, 'index'])
        ->name('staf-prodi.surat_pengantar');

    Route::post('/surat_pengantar/{id_pengajuan}/upload', [\App\Http\Controllers\StafProdi\SuratPengantarController::class, 'upload'])
        ->name('staf-prodi.surat_pengantar.upload');

    Route::get('/surat_pengantar/{id_pengajuan}/download', [\App\Http\Controllers\StafProdi\SuratPengantarController::class, 'download'])
        ->name('staf-prodi.surat_pengantar.download');

    Route::delete('/surat_pengantar/{id_pengajuan}/delete', [\App\Http\Controllers\StafProdi\SuratPengantarController::class, 'delete'])
    ->name('staf-prodi.surat_pengantar.delete');

    // ============================================
    // Data Akun
    // ============================================
    Route::get('/data_akun', [DataAkunController::class, 'index'])
        ->name('staf-prodi.data_akun');

    // LIST + SEARCH + FILTER
    Route::get('/data_akun', [DataAkunController::class, 'index'])
        ->name('staf-prodi.data_akun');

    // DELETE
    Route::delete('/data_akun/{user}', [DataAkunController::class, 'destroy'])
        ->name('staf-prodi.data_akun.delete');

    Route::delete('/staf-prodi/data-akun/{id}', [DataAkunController::class, 'destroy'])
        ->name('staf-prodi.data-akun.destroy');

    // ============================================
    // Staf Managemen
    // ============================================
    Route::get('/manajemen-staf', [StafManagementController::class, 'index'])->name('staf-prodi.manajemen_staf');
    Route::post('/manajemen-staf/store', [StafManagementController::class, 'store'])->name('staf-prodi.manajemen_staf.store');
    Route::post('/manajemen-staf/reset/{id}', [StafManagementController::class, 'resetPassword'])->name('staf-prodi.manajemen_staf.reset');
    Route::delete('/manajemen-staf/delete/{id}', [StafManagementController::class, 'destroy'])->name('staf-prodi.manajemen_staf.delete');
    
    // ============================================
    // Activity Logs
    // ============================================
    Route::get('/staf-prodi/activity-logs', [ActivityLogController::class, 'index'])->name('staf-prodi.activity_logs');


    // ============================
    // ARSIP DATA MAGANG (STAF PRODI)
    // ============================
    Route::prefix('staf-prodi')->name('staf-prodi.')->group(function () {

        // LIST
        Route::get('/arsip_data_magang', [ArsipController::class, 'index'])
            ->name('arsip_data_magang');

        // EXPORT (HARUS DI ATAS {id})
        Route::get('/arsip_data_magang/export', [ArsipController::class, 'export'])
            ->name('arsip_data_magang.export');

        // DETAIL (AJAX)
        Route::get('/arsip_data_magang/{id}/detail', [ArsipController::class, 'detail'])
            ->name('arsip_data_magang.detail');

        // DOWNLOAD DOKUMEN
        Route::get('/arsip_data_magang/{id}/download/{jenis}', [ArsipController::class, 'downloadDokumen'])
            ->name('arsip_data_magang.download');

        // DOWNLOAD laporan mingguan
        Route::get('/arsip_data_magang/laporan-mingguan/{id}/download', [ArsipController::class, 'downloadlaporanmingguan'])
            ->name('arsip_data_magang.download_laporan');
    });


    // ============================================
    // STAF PRODI FAQ
    // ============================================
    Route::prefix('staf-prodi')->name('staf-prodi.')->middleware(['auth'])->group(function () {

        // HALAMAN LIST FAQ
        Route::get('/faq', [FaqController::class, 'index'])->name('faq');

        // TAMBAH FAQ
        Route::get('/faq/tambah', [FaqController::class, 'create'])->name('tambah_faq');
        Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');

        // EDIT FAQ
        Route::get('/faq/{id_faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
        Route::put('/faq/{id_faq}', [FaqController::class, 'update'])->name('faq.update');

        // HAPUS FAQ
        Route::delete('/faq/{id_faq}', [FaqController::class, 'destroy'])->name('faq.delete');
    });

    // ============================================
    // PROFIL STAF PRODI (UBAH PASSWORD & PROFIL)
    // ============================================
    Route::get('/profil_staf', function () {
        return view('dashboard.staf-prodi.profil_staf');
    })->name('staf-prodi.profil_staf');

    // Route Update Email (Biodata)
    Route::put('/profil_staf/email', 
        [\App\Http\Controllers\StafProdi\ProfilStafController::class, 'updateEmail']
    )->name('profil.email.update');

    // Route Update Password (Link Anda tetap terjaga)
    Route::put('/profil_staf/password',
        [\App\Http\Controllers\StafProdi\ProfilStafController::class, 'updatePassword']
    )->name('profil.password.update');

});

// ===========================
// DASHBOARD DOSEN PEMBIMBING
// ===========================
Route::middleware(['auth', 'role:dosen_pembimbing'])->group(function () {

    // ✅ PAKAI CONTROLLER
    Route::get(
        '/dashboard_utama_dosen-pembimbing',
        [DashboardDosenController::class, 'index']
    )->name('dashboard.dosen-pembimbing');

    Route::get(
        '/daftar_mahasiswa_bimbingan',
        [MahasiswaBimbinganController::class, 'index']
    )->name('dosen-pembimbing.daftar_mahasiswa_bimbingan');

    Route::get(
        '/dosen/mahasiswa-bimbingan/{id_pengajuan}/detail',
        [MahasiswaBimbinganController::class, 'detailMahasiswaBimbingan']
    )->name('dosen.mahasiswa-bimbingan.detail');

    Route::get(
        '/laporan_mahasiswa',
        [LaporanMahasiswaController::class, 'index']
    )->name('dosen-pembimbing.laporan_mahasiswa');

    Route::get(
        '/laporan-mingguan/{id}',
        [LaporanMahasiswaController::class, 'showBulanan']
    )->name('dosen-pembimbing.laporan.bulanan.show');


    // ===========================
    // FIX: SIMPAN EVALUASI (INI SAJA)
    // ===========================
    Route::post(
        '/laporan-mingguan/{id}/evaluasi',
        [LaporanMahasiswaController::class, 'evaluasiBulanan']
    )->name('dosen-pembimbing.laporan.bulanan.evaluasi');

    Route::post(
        '/laporan-akhir/{id}/evaluasi',
        [LaporanMahasiswaController::class, 'evaluasiAkhir']
    )->name('dosen-pembimbing.laporan.akhir.evaluasi');

    Route::post(
        '/dosen-pembimbing/laporan-mahasiswa/{id}/review',
        [LaporanMahasiswaController::class, 'updateReview']
    )->name('dosen-pembimbing.laporan.review');

    // ===========================
    // PROFIL DOSEN PEMBIMBING
    // ===========================
    Route::get(
        '/profil_dosen',
        [ProfilDosenController::class, 'index']
    )->name('dosen-pembimbing.profil_dosen.index');

    Route::put(
        '/profil_dosen/update-biodata',
        [ProfilDosenController::class, 'updateBiodata']
    )->name('dosen-pembimbing.profil_dosen.update_biodata');

    Route::put(
        '/profil_dosen/update-password',
        [ProfilDosenController::class, 'updatePassword']
    )->name('dosen-pembimbing.profil_dosen.update_password');


});

// ===========================
// DASHBOARD MITRA PERUSAHAAN
// ===========================
Route::middleware(['auth', 'role:mitra_perusahaan'])->group(function () {

    // Dashboard Utama
    Route::get(
        '/dashboard_utama_mitra-perusahaan',
        [DashboardMitraController::class, 'index']
    )->name('dashboard.mitra-perusahaan');

    // Data Mahasiswa - UPDATED: Menggunakan Controller
    Route::get(
        '/mitra_perusahaan_data_mahasiswa',
        [DataMahasiswaController::class, 'index']
    )->name('mitra-perusahaan.mitra_perusahaan_data_mahasiswa');

    // API Search untuk Data Mahasiswa (Optional - untuk AJAX)
    Route::get(
        '/mitra_perusahaan_data_mahasiswa/search',
        [DataMahasiswaController::class, 'search']
    )->name('mitra-perusahaan.data_mahasiswa.search');

    // Penilaian
    Route::get(
        '/penilaian',
        [PenilaianController::class, 'index']
    )->name('mitra-perusahaan.penilaian');

    Route::post(
        '/penilaian/upload',
        [PenilaianController::class, 'upload']
    )->name('mitra-perusahaan.penilaian.upload');



    // Detail Mahasiswa
    Route::get(
        '/detail_mahasiswa/{npm}',
        fn($npm) => view('dashboard.mitra-perusahaan.detail_mahasiswa', ['npm' => $npm])
    )->name('mitra-perusahaan.detail_mahasiswa');

    Route::post(
        '/mitra/data-mahasiswa/update-periode',
        [DataMahasiswaController::class, 'updatePeriode']
    )->name('mitra.data_mahasiswa.update_periode');

    // Kirim Silabus - UPDATED: Menambahkan route upload
    Route::prefix('kirim_silabus')->name('mitra-perusahaan.kirim_silabus.')->group(function () {
        // Halaman utama kirim silabus
        Route::get('/', [SilabusController::class, 'index'])->name('index');

        // Upload silabus dengan file (Form POST)
        Route::post('/upload', [SilabusController::class, 'uploadSilabus'])->name('upload');

        // Kirim silabus tanpa file (AJAX - fallback/legacy)
        Route::post('/kirim', [SilabusController::class, 'kirimSilabus'])->name('kirim');

        // Get status silabus (AJAX)
        Route::get('/status/{id_pengajuan}', [SilabusController::class, 'getStatus'])->name('status');
    });

    // ===========================
    // PROFIL MITRA
    // ===========================
    Route::get(
        '/profil_mitra',
        [ProfilMitraController::class, 'index']
    )->name('mitra-perusahaan.profil_mitra.index');

    Route::put(
        '/profil_mitra/update-data',
        [ProfilMitraController::class, 'updateData']
    )->name('mitra-perusahaan.profil_mitra.update_data');

    Route::put(
        '/profil_mitra/update-password',
        [ProfilMitraController::class, 'updatePassword']
    )->name('mitra-perusahaan.profil_mitra.update_password');

});


// ============ LOGOUT ============
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');