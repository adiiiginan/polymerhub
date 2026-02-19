<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionShipment extends Model
{
    use HasFactory;

    protected $table = 'lion_shipment';

    protected $fillable = [
        'idtrans',
        'tracking_number',
        'booking_id',
        'service_type',
        'total_charge',
        'status',
        'rate_response',
        'shipper_address',
        'recipient_address',
        'weight',
        'currency',
    ];
}
