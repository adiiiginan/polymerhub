<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiProses extends Model
{
    use HasFactory;

    protected $table = 'transaksi_proses';

    protected $fillable = [
        'idtrans',
        'kode_ship',
        'status',
        'kode_inv',
        'no_resi',
        'expedisi',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idtrans');
    }
}
