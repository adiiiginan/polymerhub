<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id';


    public $timestamps = true;

    protected $fillable = [
        'idtransaksi',
        'iduser',
        'status',
        'total',
        'kode_user',
        'idbayar',
        'kode_po',
        'is_request',
        'created_at',
        'updated_at',
        'shipping_cost',
        'shipping_service',
        'address_id',
        'shipping_currency',
        'shippinh_estimate',
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'idtrans');
    }

    public function statusRelasi()
    {
        return $this->belongsTo(TransaksiStatus::class, 'status', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }


    public function items()
    {
        return $this->hasMany(TransaksiDetail::class, 'idtrans');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function shipment()
    {
        return $this->hasOne(FedexShipment::class, 'idtrans');
    }

    public function invoice()
    {
        return $this->hasOne('App\Models\TransaksiInvoice', 'idtrans');
    }
    public function request()
    {
        return $this->hasOne(TransaksiRequest::class, 'idtrans', 'id');
    }
}
