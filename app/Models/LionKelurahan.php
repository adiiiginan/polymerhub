<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LionKelurahan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lion_kelurahan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kecamatan_id',
        'nama',
    ];

    /**
     * Get the district that owns the sub-district.
     */
    public function kecamatan()
    {
        return $this->belongsTo(LionKecamatan::class, 'kecamatan_id');
    }
}
