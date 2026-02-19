<?php

namespace App\Models;

use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiInvoice extends Model
{
    use HasFactory;
    protected $table = 'transaksi_invoice';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idtrans',
        'kode_inv',
        'total',
        'status',
        'created_at',
        'updated_at',
        'faktur',
        'faktur_pajak',
        'bukti_bayar'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'idtrans');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,       // Final model
            Transaksi::class,  // Intermediate model
            'iduser',          // Foreign key on intermediate model (Transaksi.iduser -> User.id)
            'id',              // Local key on final model (User.id)
            'idtrans',         // Local key on current model (TransaksiInvoice.idtrans -> Transaksi.id)
            'id'               // Local key on intermediate model (Transaksi.id)
        );
    }

    public function statusRelasi()
    {
        return $this->belongsTo(TransaksiStatus::class, 'status', 'id');
    }

    public function proses()
    {
        return $this->hasOne(TransaksiProses::class, 'idtrans', 'idtrans');
    }
}
