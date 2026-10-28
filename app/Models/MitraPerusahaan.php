<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPerusahaan extends Model
{
    protected $table = 'mitra_perusahaan';
    protected $primaryKey = 'id_mitra';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'id_mitra',
        'id_user',
        'nama_perusahaan',
        'alamat',
        'kontak_pic',
        'no_telp_pic',
        'email_pic',
        'status_verifikasi'
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'id_mitra', 'id_mitra');
    }

    public function silabusMitra()
    {
        return $this->hasMany(
            SilabusMitra::class,
            'id_mitra',
            'id_mitra'
        );
    }

    // Scope untuk mitra yang aktif/disetujui
    public function scopeAktif($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    // TAMBAHAN: Accessor untuk status (backward compatibility)
    public function getStatusAttribute()
    {
        // Map status_verifikasi ke status
        $statusMap = [
            'pending' => 'pending',
            'disetujui' => 'aktif',
            'ditolak' => 'nonaktif',
        ];
        
        return $statusMap[$this->status_verifikasi] ?? 'pending';
    }

    public function getStatusLabelAttribute()
    {
        return $this->status_verifikasi === 'disetujui'
            ? 'TERVERIFIKASI'
            : 'MENUNGGU VERIFIKASI';
    }

}