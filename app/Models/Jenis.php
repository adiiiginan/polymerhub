<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = 'jenis';

    protected $fillable = ['id_produk', 'id_produk_jenis',];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function ukuran()
    {
        return $this->hasMany(Ukuran::class, 'id_jenis_produk');
    }
}
