<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexCommercialInvoice extends Model
{
    use HasFactory;

    protected $table = 'fedex_commercial_invoices';

    protected $fillable = [
        'order_id',
        'shipment_id',
        'invoice_number',
        'invoice_date',
        'awb_number',
        'incoterms',
        'reason_for_export',
        'currency',
        'subtotal',
        'freight',
        'insurance',
        'total_value',
        'gross_weight',
        'net_weight',
        'pdf_path',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(FedexCommercialInvoiceItem::class, 'fedex_commercial_invoice_id');
    }

    public function order()
    {
        return $this->belongsTo(Transaksi::class, 'order_id');
    }
}
