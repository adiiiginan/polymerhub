<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'phone',
        'alamat',
        'city',
        'state',
        'zip_code',
        'idcountry',
        'is_primary',
        'is_store_address',
        'kode_iso',
        'jenis_alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'idcountry', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'city', 'id');
    }
}
