<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
        protected $table = 'status';
        protected $fillable = ['status'];
        public $timestamps = false; // jika tidak ada created_at dan updated_at
}
