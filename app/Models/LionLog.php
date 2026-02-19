<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionLog extends Model
{
    use HasFactory;

    protected $table = 'lion_logs';

    protected $fillable = [
        'endpoint',
        'request_json',
        'response_json',
        'status_code',
    ];
}
