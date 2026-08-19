<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FedexTradeDocument extends Model
{
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

    protected $casts = [
        'uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship ke FedexShipment
     */
    public function fedexShipment(): BelongsTo
    {
        return $this->belongsTo(FedexShipment::class, 'shipment_id');
    }

    /**
     * Relationship ke FedexCommercialInvoice
     */
    public function commercialInvoice(): BelongsTo
    {
        return $this->belongsTo(FedexCommercialInvoice::class, 'fedex_commercial_invoice_id');
    }

    /**
     * Scope: Dokumen yang pending untuk di-upload
     */
    public function scopePending($query)
    {
        return $query->where('upload_status', 'pending');
    }

    /**
     * Scope: Dokumen yang sudah berhasil di-upload
     */
    public function scopeUploaded($query)
    {
        return $query->where('upload_status', 'success');
    }

    /**
     * Scope: Dokumen yang gagal di-upload
     */
    public function scopeFailed($query)
    {
        return $query->where('upload_status', 'failed');
    }

    /**
     * Helper: Mark as uploaded
     */
    public function markAsUploaded($fedexDocumentId = null): void
    {
        $this->update([
            'upload_status' => 'success',
            'fedex_document_id' => $fedexDocumentId ?? $this->fedex_document_id,
            'uploaded_at' => now(),
            'error_message' => null,
        ]);
    }

    /**
     * Helper: Mark as failed
     */
    public function markAsFailed($errorMessage): void
    {
        $this->update([
            'upload_status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
