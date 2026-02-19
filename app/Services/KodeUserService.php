<?php

namespace App\Services;

use App\Models\User;

class KodeUserService
{
    public function generate()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $lastNumber = $lastUser ? intval(substr($lastUser->kode_user, 3)) : 0;
        $newNumber = $lastNumber + 1;
        return 'USR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
