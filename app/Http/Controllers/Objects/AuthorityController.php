<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use Illuminate\Http\Request;

class AuthorityController extends Controller
{
    public static function getAuthorityInputs() {
        return Authority::select(['name', 'authority_id'])->orderBy('name')->get();
    }
}
