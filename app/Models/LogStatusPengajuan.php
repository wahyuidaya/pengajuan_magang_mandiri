<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatusPengajuan extends Model
{
    protected $table = 'log_status_pengajuan';
    protected $primaryKey = 'id_log';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_log',
        'id_pengajuan',
        'status_dari',
        'status_ke',
        'timestamp',
        'pelaku',
        'catatan'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class, 'id_pengajuan', 'id_pengajuan');
    }

    // Relasi ke user yang melakukan perubahan
    public function user()
    {
        return $this->belongsTo(User::class, 'pelaku', 'id_user');
    }
}