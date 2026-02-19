<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [

        'geoname_id',
        'name',
        'country_code',
        'state_code',
        'states_code',
        'latitude',
        'longitude',
        'population',
    ];
}
