<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_detail';
    protected $primaryKey = 'id';
    public $timestamps = false; // ubah ke true kalau pakai timestamps Laravel

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'kode_user',
        'email',
        'nama',
        'no_hp',
        'alamat',
        'idkuali',
        'foto',
        'idperusahaan',
        'perusahaan',
        'lengkap',
        'zip',
        'city',
        'idcountry',
        'vat',
        'jabatan',
        'kode_iso',
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class, 'idcountry', 'id');
    }

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'idcountry', 'id');
    }

    /**
     * Relasi ke tabel user (Customer)
     * user_detail.kode_user <-> user.kode_user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'kode_user', 'kode_user');
    }
}
