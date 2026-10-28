<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_user',
        'username',
        'email',
        'password_hash',
        'role',
        'is_password_changed',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_password_changed' => 'boolean',
    ];

    // Laravel login pakai username, bukan email
    public function username()
    {
        return 'username';
    }

    // Auth uses password_hash instead of password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // --- RELATIONSHIPS ---

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    public function dosenPembimbing()
    {
        return $this->hasOne(DosenPembimbing::class, 'id_user');
    }

    public function mitra()
    {
        return $this->hasOne(MitraPerusahaan::class, 'id_user');
    }

    /**
     * Override fungsi kirim email reset password ke Bahasa Indonesia
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new class($token) extends ResetPasswordNotification {
            public function toMail($notifiable)
            {
                // Menggunakan $this->token karena properti ini sudah didefinisikan 
                // di dalam parent class ResetPasswordNotification
                return (new MailMessage)
                    ->subject('Pemberitahuan Reset Password')
                    ->greeting('Halo!')
                    ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
                    ->action('Reset Password', url(config('app.url') . route('password.reset', [
                        'token' => $this->token,
                        'email' => $notifiable->getEmailForPasswordReset(),
                    ], false)))
                    ->line('Link reset password ini akan kedaluwarsa dalam 60 menit.')
                    ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.')
                    ->salutation('Salam, Tim Pengajuan Magang Mandiri');
            }
        });
    }
}