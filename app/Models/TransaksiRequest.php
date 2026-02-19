<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiRequest extends Model
{
    use HasFactory;

    protected $table = 'transaksi_request';

    protected $fillable = [
        'iduser',
        'idproduk',
        'file_request',
        'idstatus',
        'catatan',
        'idtrans',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idtrans', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idproduk', 'id');
    }
}
