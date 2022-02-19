<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    /**
     * Get Establishments by region
     *
     * @param $region_id
     * @return mixed
     */
    public static function getEstablishmentsByRegion($region_id) {
        return Establishment::where('region_id', $region_id)->limit(100)->get();
    }

    /**
     * Get Establishments by authority
     *
     * @param $authority_id
     * @return mixed
     */
    public static function getEstablishmentsByAuthority($authority_id) {
        return Establishment::where('authority_id', $authority_id)->limit(100)->get();
    }
}
