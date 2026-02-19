<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCodes extends Model
{
    use HasFactory;

    protected $table = 'postal_codes';

    protected $fillable = [
        'country_code',
        'postal_code',
        'city_name',
        'state_code',
        'latitude',
        'longitude',
    ];
}
