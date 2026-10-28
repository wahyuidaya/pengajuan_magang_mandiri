<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use App\Models\MitraPerusahaan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MagangController extends Controller
{
    public function create()
    {
        try {
            $mahasiswa = Mahasiswa::where('id_user', Auth::id())->first();

            if (!$mahasiswa) {
                return redirect()->route('dashboard.mahasiswa')
                    ->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            // ✅ Cek kelengkapan data diri (Fungsi Asli Tetap)
            $dataLengkap = $mahasiswa->kelas && $mahasiswa->ipk && $mahasiswa->no_hp && $mahasiswa->foto_url;
            if (!$dataLengkap) {
                return redirect()->route('mahasiswa.profil_mahasiswa.index')
                    ->with('warning', 'Silakan lengkapi data diri sebelum mengajukan magang.');
            }

            // ✅ Cek pengajuan aktif (Mode Read-Only & Validasi Selesai)
            $pengajuanAktif = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->where(function ($query) {
                    $query->whereIn('status', ['diajukan', 'verifikasi', 'disetujui'])
                        ->orWhere(function ($q) {
                            $q->where('status', 'selesai')
                                ->whereNotExists(function ($sq) {
                                    $sq->select(DB::raw(1))
                                        ->from('nilai_akhir')
                                        ->whereRaw('nilai_akhir.id_pengajuan = pengajuan_magang.id_pengajuan')
                                        ->whereNotNull('nilai_angka')
                                        ->whereNotNull('nilai_huruf');
                                });
                        });
                })
                ->first();

            // ✅ Logika Pengajuan Terakhir untuk Mode Lock Ditolak
            $pengajuanTerakhir = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->latest('tanggal_pengajuan')
                ->first();

            // Mode gembok aktif jika status terakhir ditolak DAN tidak ada pengajuan lain yang sedang berjalan
            $pengajuanDitolak = ($pengajuanTerakhir && $pengajuanTerakhir->status === 'ditolak' && !$pengajuanAktif) ? $pengajuanTerakhir : null;

            $mitraList = MitraPerusahaan::where('status_verifikasi', 'disetujui')
                ->orderBy('nama_perusahaan', 'asc')
                ->get();

            return view('dashboard.mahasiswa.ajukan_magang', compact(
                'mahasiswa',
                'mitraList',
                'pengajuanDitolak',
                'pengajuanAktif'
            ));

        } catch (\Exception $e) {
            return redirect()->route('dashboard.mahasiswa')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Mengambil data mahasiswa beserta data user (untuk email)
        $mahasiswa = Mahasiswa::with('user')->where('id_user', Auth::id())->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.')->withInput();
        }

        // ✅ SECURITY: Cek jika status terakhir masih ditolak (Mencegah bypass submit)
        $cekStatusDitolak = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
            ->latest('tanggal_pengajuan')
            ->first();

        if ($cekStatusDitolak && $cekStatusDitolak->status === 'ditolak') {
            return back()->with('error', 'Akses pengajuan terkunci. Silakan hubungi staf untuk membuka blokir.');
        }

        if (!$mahasiswa->foto_url) {
            return redirect()->route('mahasiswa.profil_mahasiswa.index')
                ->with('warning', 'Silakan lengkapi data diri dan unggah foto formal sebelum mengajukan magang.');
        }

        // ✅ Validasi input (Tetap Utuh sesuai spesifikasi Anda)
        $validated = $request->validate([
            'ipk' => 'required|numeric|min:0|max:4',
            'nama_perusahaan' => 'required|string|max:50',
            'kelas' => 'required|string|max:20',
            'alamat_perusahaan' => 'required|string',
            'posisi' => 'required|string|max:30',
            'nomor_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'semester' => 'required|string',
            'krs_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'transkrip_nilai_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'surat_pengantar_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'ipk.required' => 'IPK wajib diisi',
            'kelas.required' => 'Kelas wajib diisi',
            'nomor_hp.required' => 'Nomor HP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'semester.required' => 'Semester wajib dipilih',
            'krs_file.required' => 'File KRS wajib diunggah',
            'transkrip_nilai_file.required' => 'File Transkrip Nilai wajib diunggah',
            'surat_pengantar_file.required' => 'File Surat Pengantar wajib diunggah',
        ]);

        DB::beginTransaction();
        try {
            // Update data Mahasiswa
            $mahasiswa->update([
                'ipk' => $validated['ipk'],
                'no_hp' => $validated['nomor_hp'],
                'kelas' => $validated['kelas'],
            ]);

            // Update Email di tabel Users
            $mahasiswa->user->update([
                'email' => $validated['email']
            ]);

            // Cek pengajuan aktif (Double Check Backend)
            $pengajuanAktif = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->whereIn('status', ['diajukan', 'verifikasi', 'disetujui'])
                ->first();

            if ($pengajuanAktif) {
                DB::rollBack();
                return back()->with('error', 'Anda sudah memiliki pengajuan magang aktif!')->withInput();
            }

            // Generate ID Pengajuan
            $idPengajuan = (string) Str::uuid();

            // Upload file (Logika Asli)
            $npm = $mahasiswa->npm;
            $timestamp = time();

            $krsPath = $request->file('krs_file')->storeAs(
                'dokumen/krs',
                $npm . '_' . $timestamp . '_krs.' . $request->file('krs_file')->extension(),
                'public'
            );

            $transkripPath = $request->file('transkrip_nilai_file')->storeAs(
                'dokumen/transkrip',
                $npm . '_' . $timestamp . '_transkrip.' . $request->file('transkrip_nilai_file')->extension(),
                'public'
            );

            $suratPath = $request->file('surat_pengantar_file')->storeAs(
                'dokumen/surat',
                $npm . '_' . $timestamp . '_surat.' . $request->file('surat_pengantar_file')->extension(),
                'public'
            );

            // Simpan pengajuan
            PengajuanMagang::create([
                'id_pengajuan' => $idPengajuan,
                'id_mhs' => $mahasiswa->id_mhs,
                'id_mitra' => null,
                'nama_perusahaan' => $validated['nama_perusahaan'],
                'alamat_perusahaan' => $validated['alamat_perusahaan'],
                'posisi_dilamar' => $validated['posisi'],
                'krs_url' => $krsPath,
                'transkrip_url' => $transkripPath,
                'surat_pengantar_mahasiswa' => $suratPath,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now(),
                'periode_magang' => null,
                'semester' => $validated['semester'],
            ]);

            DB::commit();
            return redirect()->route('mahasiswa.ajukan_magang')
                ->with('success', 'Pengajuan magang berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Cleanup file jika gagal insert DB
            if (isset($krsPath))
                Storage::disk('public')->delete($krsPath);
            if (isset($transkripPath))
                Storage::disk('public')->delete($transkripPath);
            if (isset($suratPath))
                Storage::disk('public')->delete($suratPath);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show()
    {
        try {
            $mahasiswa = Mahasiswa::with('user')->where('id_user', Auth::id())->firstOrFail();
            $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->with(['mitra', 'mahasiswa', 'logs'])
                ->latest('tanggal_pengajuan')
                ->first();

            if (!$pengajuan) {
                return redirect()->route('mahasiswa.ajukan_magang')
                    ->with('info', 'Anda belum memiliki pengajuan magang.');
            }

            return view('dashboard.mahasiswa.detail_pengajuan', compact('pengajuan', 'mahasiswa'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.mahasiswa')->with('error', 'Gagal memuat detail.');
        }
    }

    public function statusPengajuan()
    {
        try {
            $mahasiswa = Mahasiswa::with('user')->where('id_user', Auth::id())->firstOrFail();
            $pengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->with(['mitra', 'mahasiswa', 'logs'])
                ->latest('tanggal_pengajuan')
                ->first();

            if (!$pengajuan) {
                return redirect()->route('mahasiswa.ajukan_magang')
                    ->with('info', 'Anda belum memiliki pengajuan magang.');
            }

            $timeline = $this->generateTimeline($pengajuan);
            return view('dashboard.mahasiswa.status_pengajuan', compact('pengajuan', 'mahasiswa', 'timeline'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.mahasiswa')->with('error', 'Gagal memuat status.');
        }
    }

    public function riwayatMagang()
    {
        try {
            $mahasiswa = Mahasiswa::with('user')->where('id_user', Auth::id())->firstOrFail();
            $riwayatPengajuan = PengajuanMagang::where('id_mhs', $mahasiswa->id_mhs)
                ->with(['mitra', 'logs'])
                ->orderBy('tanggal_pengajuan', 'desc')
                ->paginate(10);

            return view('dashboard.mahasiswa.riwayat_magang', compact('riwayatPengajuan', 'mahasiswa'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard.mahasiswa')->with('error', 'Gagal memuat riwayat.');
        }
    }

    private function generateTimeline($pengajuan)
    {
        return [
            [
                'status' => 'draft',
                'label' => 'Draft',
                'icon' => 'edit',
                'completed' => in_array($pengajuan->status, ['diajukan', 'verifikasi', 'revisi', 'disetujui', 'ditolak', 'selesai']),
                'active' => $pengajuan->status === 'draft',
            ],
            [
                'status' => 'diajukan',
                'label' => 'Diajukan',
                'icon' => 'send',
                'completed' => in_array($pengajuan->status, ['verifikasi', 'revisi', 'disetujui', 'ditolak', 'selesai']),
                'active' => $pengajuan->status === 'diajukan',
            ],
            [
                'status' => 'verifikasi',
                'label' => 'Verifikasi',
                'icon' => 'clock',
                'completed' => in_array($pengajuan->status, ['revisi', 'disetujui', 'ditolak', 'selesai']),
                'active' => $pengajuan->status === 'verifikasi',
            ],
            [
                'status' => 'final',
                'label' => $pengajuan->status === 'disetujui' ? 'Disetujui' : ($pengajuan->status === 'ditolak' ? 'Ditolak' : ($pengajuan->status === 'revisi' ? 'Revisi' : 'Selesai')),
                'icon' => $pengajuan->status === 'disetujui' ? 'check-circle' : ($pengajuan->status === 'ditolak' ? 'x-circle' : ($pengajuan->status === 'selesai' ? 'check-circle' : 'alert-circle')),
                'completed' => in_array($pengajuan->status, ['disetujui', 'ditolak', 'selesai']),
                'active' => in_array($pengajuan->status, ['revisi', 'disetujui', 'ditolak', 'selesai']),
            ],
        ];
    }
}