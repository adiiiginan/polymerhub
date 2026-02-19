<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexShipment extends Model
{
    use HasFactory;

    protected $table = 'fedex_shipments';

    protected $fillable = [
        'idtrans',
        'shipment_id',
        'tracking_number',
        'label_url',
        'service_type',
        'total_charge',
        'currency',
        'rate_response',
        'shipper_address',
        'recipient_address',
        'weight',
        'status',
    ];
}
