<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'shipping_rate';

    protected $fillable = [
        'idexpedisi',
        'carrier',
        'service_type',
        'origin',
        'destination',
        'weight',
        'price',
        'currency',
        'etd',
        'response_json',
        'created_at',
        'updated_at',
    ];
}
