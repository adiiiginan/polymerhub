<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexCommercialInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'fedex_commercial_invoice_items';

    protected $fillable = [
        'fedex_commercial_invoice_id',
        'description',
        'hs_code',
        'country_of_origin',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];

    public function commercialInvoice()
    {
        return $this->belongsTo(FedexCommercialInvoice::class, 'fedex_commercial_invoice_id');
    }
}
