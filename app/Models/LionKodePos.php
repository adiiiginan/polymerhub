<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionKodePos extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lion_kode_pos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kelurahan_id',
        'kode_pos',
    ];

    /**
     * Get the sub-district that owns the postal code.
     */
    public function kelurahan()
    {
        return $this->belongsTo(LionKelurahan::class, 'kelurahan_id');
    }
}
