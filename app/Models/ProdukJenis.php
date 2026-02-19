<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukJenis extends Model
{
    use HasFactory;

    protected $table = 'produk_jenis'; // master jenis (Rod, Sheet, Tape, Tube)

    protected $fillable = [
        'jenis',
    ];

    // Relasi ke Produk lewat pivot "jenis"
    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_shape');
    }

    // Relasi ke Ukuran (produk_ukuran)
    public function ukurans()
    {
        return $this->hasMany(Ukuran::class, 'id_produk_jenis');
    }

    public function stoks()
    {
        return $this->hasMany(ProdukStok::class, 'id_jenis');
    }
}
