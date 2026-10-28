<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PenilaianMagang extends Model
{
    protected $table = 'penilaian_magang';
    protected $primaryKey = 'id_penilaian';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'id_penilaian',
        'id_pengajuan',
        'file_penilaian',
        'tanggal_upload_mitra',
        'nilai_akhir',
        'tanggal_input_prodi',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function isTerisi(): bool
    {
        return !empty($this->file_penilaian)
            || !empty($this->nilai_akhir);
    }
}