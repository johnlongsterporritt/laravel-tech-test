<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authority extends Model
{
    use HasFactory;

    protected $table = 'authorities';

    protected $fillable = [
        'name',
        'region_id',
        'establishment_count',
        'authority_id'
    ];

    /**
     * The Authorities Region
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
