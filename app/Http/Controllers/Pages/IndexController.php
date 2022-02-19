<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Objects\AuthorityController;
use App\Http\Controllers\Objects\EstablishmentController;
use App\Http\Controllers\Objects\ReigonController;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function show() {
        return view('index')->with([
            'regions' => ReigonController::getRegionInputs(),
            'authorities' => AuthorityController::getAuthorityInputs()
        ]);
    }

    public function search(Request $request) {
        $validatedData = $request->validate([
            'region' => 'int|nullable',
            'authority' => 'int|nullable',
            'sort' => 'nullable',
        ]);

        $region = null;
        $authority = null;
        $establishments = null;
        $sort = $validatedData['sort'] ?? null;

        if (isset($validatedData['region'])) {
            if ($region = $validatedData['region']) {
                $establishments = EstablishmentController::getEstablishmentsByRegion($region, $sort);
            }
        }

        if (isset($validatedData['authority'])) {
            if ($authority = $validatedData['authority']) {
                $establishments = EstablishmentController::getEstablishmentsByAuthority($authority, $sort);
            }
        }

        return view('index')->with([
            'regionSelect' => $region,
            'authoritySelect' => $authority,
            'regions' => ReigonController::getRegionInputs(),
            'authorities' => AuthorityController::getAuthorityInputs(),
            'establishments' => $establishments
        ]);
    }
}
