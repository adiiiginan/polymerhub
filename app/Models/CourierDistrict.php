<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierDistrict extends Model
{
    use HasFactory;

    protected $table = 'courier_district';

    protected $fillable = [
        'city_id',
        'district_name',
        'full_name',
    ];

    public $timestamps = false;
}
