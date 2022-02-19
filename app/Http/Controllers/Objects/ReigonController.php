<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class ReigonController extends Controller
{
    public static function getRegionInputs() {
        return Region::select(['name', 'fhrs_id'])->orderBy('name')->get();
    }
}
