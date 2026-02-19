<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false; // ubah ke true kalau kolom created_at & updated_at pakai format timestamp Laravel

    /**
     * Kolom yang boleh diisi secara mass-assignment (fillable)
     */
    protected $fillable = [
        'id',
        'password_hash',
        'username',
        'auth_key',
        'password_reset_token',
        'status',
        'created_at',
        'updated_at',
        'verification_token',
        'id_priviladges',
        'email',
        'kode_user',
        'password_confirmation',
        'password',
        'comment',
    ];

    /**
     * Kolom yang disembunyikan saat model di-serialize (misal ke JSON)
     */
    protected $hidden = [
        'password_hash',
        'password',
        'auth_key',
        'remember_token',
    ];

    /**
     * Override kolom password agar Auth::attempt() pakai password_hash
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Relasi ke tabel user_detail
     * user.kode_user  <->  user_detail.kode_user
     */
    public function detail()
    {
        return $this->hasOne(UserDetail::class, 'kode_user', 'kode_user');
    }

    /**
     * Get the user's addresses.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'iduser', 'id');
    }
}
