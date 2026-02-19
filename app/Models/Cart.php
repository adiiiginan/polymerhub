<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    // tabel cart kamu tidak punya created_at/updated_at

    protected $fillable = [
        'iduser',
        'status',
        'total',
        'address_id',
        'shipping_cost',
        'shipping_service',
        'shipping_currency',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'harga' => 'decimal:2',

    ];
    public function items()
    {
        return $this->hasMany(CartItem::class, 'idcart');
    }

    // kalau butuh relasi status transaksi, jangan pakai nama 'status' (bentrok field)
    public function statusTransaksi()
    {
        return $this->belongsTo(TransaksiStatus::class, 'status');
    }
}
