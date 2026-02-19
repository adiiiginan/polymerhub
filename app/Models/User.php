<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Models\UserPrivileges;
use App\Models\Negara;
use App\Models\Perusahaan;
use App\Models\Status;
use App\Models\UserDetail;
use App\Models\UserAddress;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     */

    protected $table = 'user'; // sesuai nama table di database

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'kode_user',
        'id_priviladges',
        'status',
        'password_hash',
        'password',
        'password_confirmation',
        'auth_key',
        'comment',
        'verification_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
        'password_repeat',
        'auth_key',
        'password_reset_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'integer',
        'id_priviladges' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the password for authentication (Laravel Auth)
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }



    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password_hash'] = Hash::make($value);
        }
    }

    /**
     * Handle created_at attribute - support different formats
     */
    public function getCreatedAtAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        // Jika value adalah Unix timestamp (integer)
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // Jika value adalah string datetime
        if (is_string($value)) {
            return Carbon::parse($value);
        }

        // Jika sudah Carbon instance
        if ($value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value);
    }

    /**
     * Handle updated_at attribute - support different formats
     */
    public function getUpdatedAtAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        // Jika value adalah Unix timestamp (integer)
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // Jika value adalah string datetime
        if (is_string($value)) {
            return Carbon::parse($value);
        }

        // Jika sudah Carbon instance
        if ($value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value);
    }

    /**
     * Get status text attribute
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            1 => 'Aktif',
            0 => 'Tidak Aktif',
            10 => 'Pending',
            9 => 'Deleted',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            1 => 'success',
            0 => 'secondary',
            10 => 'warning',
            9 => 'danger',
            default => 'light'
        };
    }

    /**
     * Get privilege name attribute
     */
    public function getPrivilegeNameAttribute()
    {
        return $this->priviladges->priviladges ?? 'No Privilege';
    }

    /**
     * Get privilege badge class
     */
    public function getPrivilegeBadgeAttribute()
    {
        return match ($this->id_priviladges) {
            1 => 'danger',   // Super Admin
            2 => 'warning',  // Admin
            3 => 'info',     // Manager
            4 => 'primary',  // User
            5 => 'secondary', // Guest
            default => 'light'
        };
    }

    /**
     * Get the user's privilege.
     */
    public function priviladges()
    {
        return $this->belongsTo(UserPrivileges::class, 'id_priviladges');
    }

    /**
     * Get the user's detail.
     */
    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'kode_user', 'kode_user');
    }


    /**
     * Get the user's status.
     */
    public function stat()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    /**
     * Get the user's detail.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'iduser', 'id');
    }

    public function transaksiRequests()
    {
        return $this->hasMany(TransaksiRequest::class, 'iduser', 'id');
    }
}
