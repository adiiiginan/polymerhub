<?php

namespace App\Models;

use App\Models\ProdukJenis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{

    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'sku',
        'deskripsi',
        'merk',

        'idsatuan',
        'weight',
        'length',
        'width',
        'height',
        'gros',
        'gambar',
        'status_aktif',
        'tempratur',
        'id_environmant', // pakai sesuai kolom di DB
        'pressure',
        'eu1935',
        'fda',
        'usp',
        'mating',
        'id_kat',
        'max_pv',
        'maximum_p',
        'max_v',
        'elongation',
        'deformation',
        'tensile',
        'spesific',
        'friction',
        'id_jenis',

    ];

    public function variants()
    {
        return $this->hasMany(ProdukStok::class, 'id_produk');
    }


    public function kategori()
    {
        return $this->belongsTo(ProdukKategori::class, 'id_kat');
    }

    public function shape()
    {
        return $this->belongsTo(ProdukJenis::class, 'id_jenis');
    }

    public function envi()
    {
        return $this->belongsTo(ProdukEnvi::class, 'id_environmant');
    }

    public function getAllUkuransAttribute()
    {
        $ukurans = collect();

        foreach ($this->jenis as $jenis) {
            if ($jenis && $jenis->ukurans) {
                $ukurans = $ukurans->merge($jenis->ukurans);
            }
        }

        return $ukurans;
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'idproduk', 'id');
    }

    public function requests()
    {
        return $this->hasMany(TransaksiRequest::class, 'idproduk', 'id');
    }
}
