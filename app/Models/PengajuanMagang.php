<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\laporanmingguan;
use App\Models\LaporanAkhir;
use App\Models\NilaiAkhir;

class PengajuanMagang extends Model
{
    protected $table = 'pengajuan_magang';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pengajuan',
        'id_mhs',
        'id_mitra',
        'id_dosen',
        'nama_perusahaan',
        'alamat_perusahaan',
        'posisi_dilamar',
        'periode_magang',
        'semester',
        'surat_pengantar_mahasiswa',
        'surat_pengantar_prodi',
        'krs_url',
        'transkrip_url',
        'silabus_url',
        'laporan_mingguan_url',
        'laporan_akhir_url',
        'status',
        'tanggal_pengajuan',
        'catatan_verifikator',
        'catatan_dosen',
        'nilai_mitra',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
    ];

    // Relasi
    
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }
    

    public function mitra()
    {
        return $this->belongsTo(MitraPerusahaan::class, 'id_mitra', 'id_mitra');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(DosenPembimbing::class, 'id_dosen', 'id_dosen');
    }

    public function logs()
    {
        return $this->hasMany(LogStatusPengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function penilaian()
    {
        return $this->hasOne(PenilaianMagang::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function laporanmingguan()
    {
        return $this->hasMany(
            laporanmingguan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function laporanAkhir()
    {
        return $this->hasOne(
            LaporanAkhir::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

        public function penilaian_magang()
        {
            return $this->hasOne(PenilaianMagang::class, 'id_pengajuan', 'id_pengajuan');
        }


    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhir::class, 'id_pengajuan', 'id_pengajuan');
    }

    // ==========================================
    // HELPER METHODS - SURAT PENGANTAR
    // ==========================================

    /**
     * Cek apakah mahasiswa sudah upload surat pengantar
     */
    public function hasSuratMahasiswa()
    {
        return !empty($this->surat_pengantar_mahasiswa);
    }

    /**
     * Cek apakah staf prodi sudah upload surat pengantar
     */
    public function hasSuratProdi()
    {
        return !empty($this->surat_pengantar_prodi);
    }

    /**
     * Get URL surat pengantar mahasiswa
     */
    public function getSuratMahasiswaUrl()
    {
        return $this->hasSuratMahasiswa()
            ? asset('storage/' . $this->surat_pengantar_mahasiswa)
            : null;
    }

    /**
     * Get URL surat pengantar prodi
     */
    public function getSuratProdiUrl()
    {
        return $this->hasSuratProdi()
            ? asset('storage/' . $this->surat_pengantar_prodi)
            : null;
    }

    /**
     * Cek apakah staf prodi bisa upload surat pengantar
     * Syarat: status disetujui & mahasiswa sudah upload
     */
    public function canUploadSuratProdi()
    {
        return $this->status === 'disetujui' && $this->hasSuratMahasiswa();
    }

    // ==========================================
    // HELPER METHODS - STATUS
    // ==========================================

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>',
            'diajukan' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diajukan</span>',
            'verifikasi' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Verifikasi</span>',
            'revisi' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Perlu Revisi</span>',
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">' . ucfirst($this->status) . '</span>';
    }

    /**
     * Cek apakah bisa diedit
     */
    public function canEdit()
    {
        return in_array($this->status, ['draft', 'revisi']);
    }

    /**
     * Cek apakah bisa dibatalkan
     */
    public function canCancel()
    {
        return in_array($this->status, ['draft', 'diajukan']);
    }

    /**
     * Relasi ke KonversiSks
     */
    public function konversiSks()
        {
            return $this->hasMany(KonversiSks::class, 'id_pengajuan', 'id_pengajuan')
                        ->orderBy('created_at', 'desc');
        }

    /**
     * Relasi ke silabus mitra
     */
    public function silabusMitra()
    {
        return $this->hasOne(SilabusMitra::class, 'id_pengajuan', 'id_pengajuan');
    }

    // ==========================================
    // STATUS LAPORAN (DINAMIS)
    // ==========================================
    public function getStatusLaporanBadgeAttribute()
    {
        // prioritas laporan akhir
        if ($this->laporanAkhir) {
            return $this->badgeStatus($this->laporanAkhir->status);
        }

        // ambil laporan mingguan terakhir berdasarkan minggu_ke
        $laporanTerakhir = $this->laporanmingguan()
            ->orderBy('minggu_ke', 'desc')
            ->first();

        if ($laporanTerakhir) {
            return $this->badgeStatus($laporanTerakhir->status);
        }

        return $this->badgeStatus('pending');
    }

    private function badgeStatus($status)
    {
        return match ($status) {
            'disetujui' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'ditolak' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Revisi</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Pending</span>',
        };
    }

    public function getTanggalUploadFormattedAttribute()
    {
        return $this->tanggal_upload_mitra
            ? \Carbon\Carbon::parse($this->tanggal_upload_mitra)->format('Y-m-d')
            : null;
    }
}

class PenilaianMagang extends Model
{
    protected $table = 'penilaian_magang';
    protected $primaryKey = 'id_penilaian';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'tanggal_upload_mitra' => 'datetime',
    ];

        public function getTerisiAttribute(): bool
    {
        return !empty($this->file_penilaian)
            || !empty($this->nilai_akhir);
    }
}
