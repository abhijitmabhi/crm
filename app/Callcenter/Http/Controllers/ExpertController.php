<?php

namespace LocalheroPortal\Callcenter\Http\Controllers;

use Illuminate\Http\Request;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class ExpertController extends Controller
{
    public function index()
    {
        $experts = User::byRole(RoleType::EXPERT)->get();
        return view('experts.index', compact('experts'));
    }

    /**
     * @param User $expert
     */
    public function show(User $expert, Request $request)
    {
        $lead = $request->query('lead');
        $experts = User::byRole(RoleType::EXPERT)->get();
        return view('experts.show', compact('expert', 'experts', 'lead'));
    }

}