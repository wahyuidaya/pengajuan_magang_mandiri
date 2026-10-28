<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_dosen';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_dosen',
        'id_user',
        'nama_dosen',
        'email',
    ];

    protected $hidden = [
        'id_user',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Pengajuan Magang
    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'id_dosen', 'id_dosen');
    }

    // Relasi ke Nilai Akhir
    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, 'id_dosen', 'id_dosen');
    }

    // Helper: Hitung total mahasiswa bimbingan
    public function getTotalMahasiswaBimbinganAttribute()
    {
        return $this->pengajuanMagang()
            ->whereIn('status', ['disetujui', 'selesai'])
            ->count();
    }

    // Helper: Hitung mahasiswa bimbingan aktif
    public function getMahasiswaAktifAttribute()
    {
        return $this->pengajuanMagang()
            ->where('status', 'disetujui')
            ->count();
    }

    // Helper: Hitung mahasiswa bimbingan selesai
    public function getMahasiswaSelesaiAttribute()
    {
        return $this->pengajuanMagang()
            ->where('status', 'selesai')
            ->count();
    }
}