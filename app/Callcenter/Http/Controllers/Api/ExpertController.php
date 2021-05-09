<?php

namespace LocalheroPortal\Callcenter\Http\Controllers\Api;

use Illuminate\Http\Request;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class ExpertController extends Controller
{
    public function get(Request $request)
    {
        $experts = User::byRole(RoleType::EXPERT)->get();
        return response()->json($experts);
    }
}