<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonversiSks extends Model
{
    protected $table = 'konversi_sks';
    protected $primaryKey = 'id_konversi';
    public $incrementing = false; // Karena pakai UUID
    protected $keyType = 'string';

    // Hanya pakai created_at
    const UPDATED_AT = null;
    public $timestamps = true;

    protected $fillable = [
        'id_konversi',
        'id_pengajuan',
        'nama_file',
        'path_file',
        'uploaded_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by', 'id');
    }
}