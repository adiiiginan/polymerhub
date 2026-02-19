<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FedexLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'fedex_logs';

    protected $fillable = [
        'endpoint',
        'request_json',
        'response_json',
        'status_code',
        'status',
        'created_at',
        'updated_at',

    ];
}
