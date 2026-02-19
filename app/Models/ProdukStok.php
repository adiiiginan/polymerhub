<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukStok extends Model
{
    use HasFactory;

    protected $table = 'produk_stok';

    protected $fillable = [
        'id_produk',
        'id_jenis',
        'id_ukuran',
        'stok',
        'harga',
        'hargi',
        'weight',
        'length',
        'width',
        'height',
        'gros',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function jenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'id_jenis');
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran');
    }
}
