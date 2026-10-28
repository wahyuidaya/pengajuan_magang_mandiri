<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
class StafProdi extends Authenticatable
{
    use HasFactory;
    protected $table = 'staf_prodi';
    protected $fillable = [
        'username',
        'password',
        'nama',
        'email',
        'telepon'
    ];
    protected $hidden = [
        'password',
    ];
}
