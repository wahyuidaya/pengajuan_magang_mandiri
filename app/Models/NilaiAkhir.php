<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiAkhir extends Model
{
    use HasFactory;

    protected $table = 'nilai_akhir';

    protected $primaryKey = 'id_nilai';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_nilai',
        'id_pengajuan',
        'id_dosen',
        'nilai_huruf',
        'nilai_angka',
        'penilaian_mitra',
        'evaluasi_laporan',
        'penilaian_dosen',
        'tanggal_penetapan',
        'periode',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'datetime',
        'nilai_angka' => 'decimal:2',
    ];

    // =====================
    // RELASI YANG BENAR
    // =====================

    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(
            \App\Models\DosenPembimbing::class,
            'id_dosen',
            'id_dosen'
        );
    }
}
