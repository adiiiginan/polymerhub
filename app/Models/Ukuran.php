<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Ukuran extends Model
{
    protected $table = 'produk_ukuran';
    protected $fillable = ['id_produk_jenis', 'nama_ukuran',];

    public function jenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'id_produk_jenis');
    }

    public function stoks()
    {
        return $this->hasMany(ProdukStok::class, 'id_ukuran');
    }
}
