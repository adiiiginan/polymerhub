<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negara extends Model
{
    use HasFactory;

    protected $table = 'negara';
    protected $primaryKey = 'id'; // 🔥 ubah ke kolom id
    public $incrementing = true;
    protected $keyType = 'int';

    public function userDetails()
    {
        return $this->hasMany(UserDetail::class, 'idcountry', 'id');
    }
}
