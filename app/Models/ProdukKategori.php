<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKategori extends Model
{
    use HasFactory;

    protected $table = 'produk_kategori'; // nama tabel

    protected $fillable = [
        'kategori' // sesuaikan dengan kolom di tabel

    ];
}
