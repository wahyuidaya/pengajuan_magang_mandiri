<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SilabusMitra extends Model
{
    protected $table = 'silabus_mitra';
    protected $primaryKey = 'id_silabus';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_silabus',
        'id_pengajuan',
        'id_mitra',
        'judul_silabus',
        'file_silabus',
        'deskripsi',
        'status',
        'tanggal_upload'
    ];

    // =====================
    // RELATIONS
    // =====================

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function mitra()
    {
        return $this->belongsTo(
            MitraPerusahaan::class,
            'id_mitra',
            'id_mitra'
        );
    }
}
