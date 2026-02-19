<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model


{
    use HasFactory;
    
    protected $table = 'perusahaan';

    public function user()
    {
        return $this->hasMany(UserDetail::class, 'idperusahaan', 'id');
    }
    
}
