<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukCategory extends Model
{
    protected $table    = 'produk_category';
    protected $fillable = ['category'];
    public $timestamps  = false;
}
