<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierCities extends Model
{
    use HasFactory;

    protected $table = 'courier_cities';

    protected $fillable = [
        'city_code',
        'city_name',
        'province_name',
        'is_active',
    ];
}
