<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexToken extends Model
{
    use HasFactory;

    // public $timestamps = false; // Enable timestamps
    protected $table = 'fedex_tokens';

    protected $fillable = [
        'access_token',
        'token_type',
        'expires_in',
    ];
}
