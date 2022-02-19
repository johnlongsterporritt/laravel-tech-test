<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    protected $table = 'establishments';

    protected $fillable = [
        'name',
        'business_type',
        'address',
        'phone',
        'rating',
        'region_id',
        'authority_id',
        'fhrs_id'
    ];
}
