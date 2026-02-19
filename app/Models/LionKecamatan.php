<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionKecamatan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lion_kecamatan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kota_id',
        'nama',
    ];

    /**
     * Get the city that owns the district.
     */
    public function kota()
    {
        return $this->belongsTo(LionKota::class, 'kota_id');
    }
}
