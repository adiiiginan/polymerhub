<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\ProdukJenis;
use App\Models\Ukuran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';

    public $timestamps = true;
    protected $fillable = [

        'idtrans',
        'iduser',
        'idproduk',
        'id_jenis',
        'id_ukuran',
        'harga',
        'qty',
        'gros',
        'weight',
        'length',
        'width',
        'height',
        'created_at',
        'updated_at'
    ];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idtrans', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idproduk', 'id');
    }

    public function jenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'id_jenis', 'id');
    }



    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'id_ukuran');
    }
}
