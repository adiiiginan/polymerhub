<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukEnvi extends Model
{
    use HasFactory;

    protected $table = 'produk_envi';   // nama tabel
    protected $primaryKey = 'id_environmant'; // sesuaikan dengan kolom PK

    protected $fillable = [
        'envi'
    ];

    public $timestamps = false; // kalau tabel ini tidak pakai created_at & updated_at
}
