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
    public static function getEstablishmentsByRegion($region_id, $sort = null) {
        if ($sort) {
            $sortSplit = explode(',', $sort);
        } else {
            $sortSplit[] = 'name';
            $sortSplit[] = 'desc';
        }

        return Establishment::where('region_id', $region_id)->limit(500)->orderBy($sortSplit[0], $sortSplit[1])->get();
    }

    /**
     * Get Establishments by authority
     *
     * @param $authority_id
     * @return mixed
     */
    public static function getEstablishmentsByAuthority($authority_id, $sort) {
        if ($sort) {
            $sortSplit = explode(',', $sort);
        } else {
            $sortSplit[] = 'name';
            $sortSplit[] = 'desc';
        }

        return Establishment::where('authority_id', $authority_id)->orderBy($sortSplit[0], $sortSplit[1])->limit(500)->get();
    }
}
