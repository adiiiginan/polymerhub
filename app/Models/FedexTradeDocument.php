<?php

namespace App\Models;

use App\Models\FedexCommercialInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexTradeDocument extends Model
{
    use HasFactory;

    protected $table = 'fedex_trade_documents';

    protected $fillable = [
        'shipment_id',
        'fedex_commercial_invoice_id',
        'document_type',
        'file_path',
        'fedex_document_id',
        'upload_status',
        'error_message',
        'uploaded_at',
    ];

    /**
     * Get the commercial invoice associated with the FedEx trade document.
     */
    public function commercialInvoice()
    {
        return $this->belongsTo(FedexCommercialInvoice::class, 'fedex_commercial_invoice_id');
    }
}
