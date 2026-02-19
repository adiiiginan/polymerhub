<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_item';


    protected $fillable = [
        'idcart',
        'idproduk',
        'id_jenis',
        'id_ukuran',
        'qty',
        'harga',
        'hargi',
        'weight',
        'length',
        'width',
        'height',
        'gros',
        'created_at',
        'updated_at'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'idcart');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idproduk');
    }

    public function jenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'id_jenis', 'id');
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran');
    }

    public function getPriceAttribute()
    {
        return $this->attributes['harga'];
    }

    public function produkStok()
    {
        return $this->hasOne(ProdukStok::class, 'id_produk', 'idproduk')
            ->where('id_jenis', $this->id_jenis)
            ->where('id_ukuran', $this->id_ukuran);
    }
}
