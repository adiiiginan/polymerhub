<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserPrivileges extends Model


{
    protected $table = 'user_priviladges';
    protected $fillable = ['priviladges'];
    public $timestamps = false; // jika tidak ada created_at dan updated_at
}
