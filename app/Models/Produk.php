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
        'id_cat',
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
        'tygon_size_category',
        'inner_diameter',
        'outer_diameter',
        'wall_thickness',
        'min_bend_radius',
        'tygon_length',
        'tygon_working_pressure_73',
        'tygon_working_pressure_320',
        'tygon_vacuum_73',
        'tygon_vacuum_320'

    ];

    public function category()
    {
        return $this->belongsTo(ProdukCategory::class, 'id_cat');
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



    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'idproduk', 'id');
    }

    public function requests()
    {
        return $this->hasMany(TransaksiRequest::class, 'idproduk', 'id');
    }

    // Tambahkan di bawah relasi variants()
    public function variants()
    {
        return $this->hasMany(ProdukStok::class, 'id_produk');
    }
}
