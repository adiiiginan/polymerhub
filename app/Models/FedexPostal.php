<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexPostal extends Model
{
    use HasFactory;

    protected $table = 'fedex_postal_codes';

    protected $fillable = [
        'country_name',
        'country_code',
        'begin_postal_code',
        'end_postal_code',
        'intra_pickup_parcel',
        'intra_pickup_freight',
        'intra_delivery_parcel',
        'intra_delivery_freight',
        'created_at',
        'updated_at',

    ];
}
