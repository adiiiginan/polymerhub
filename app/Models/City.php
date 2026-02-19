<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'kota';

    protected $fillable = [
        'country_id',
        'nama_kota',
        'zip_code',
        'latitude',
        'longitude',
        'state_id',
    ];

    public function country()
    {
        return $this->belongsTo(Negara::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
