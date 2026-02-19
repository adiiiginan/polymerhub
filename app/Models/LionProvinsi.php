<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionProvinsi extends Model
{
    use HasFactory;

    protected $table = 'lion_provinsi';

    protected $fillable = [
        'nama',
    ];
}
