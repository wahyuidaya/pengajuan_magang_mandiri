<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanAkhir extends Model
{
    use HasFactory;

    protected $table = 'laporan_akhir';
    protected $primaryKey = 'id_laporan_akhir';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_laporan_akhir',
        'id_pengajuan',
        'judul_laporan',
        'deskripsi',
        'file_path',
        'tanggal_upload',
        'status',
        'catatan_reviewer',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    // Relasi ke PengajuanMagang
    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'disetujui' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }
}