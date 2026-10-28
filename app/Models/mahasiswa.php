<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mhs';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'id_mhs',
        'id_user',
        'npm',
        'nama_lengkap',
        'kelas',
        'email',
        'no_hp',
        'aktif',
        'ipk',
        'sks_lulus',
        'prodi',
        'foto_url'
    ];

    protected $casts = [
        'ipk' => 'decimal:2',
        'sks_lulus' => 'integer',
        'aktif' => 'boolean'
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'id_mhs', 'id_mhs');
    }

    // Accessor untuk backward compatibility (supaya $mahasiswa->nama tetap jalan)
    public function getNamaAttribute()
    {
        return $this->nama_lengkap;
    }
}