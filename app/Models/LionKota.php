<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionKota extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lion_kota';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'provinsi_id',
        'nama',
    ];

    /**
     * Get the province that owns the city.
     */
    public function provinsi()
    {
        return $this->belongsTo(LionProvinsi::class, 'provinsi_id');
    }
}
