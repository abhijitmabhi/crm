<?php

namespace LocalheroPortal\Callcenter\Http\Controllers;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LeadRepository;
use LocalheroPortal\Core\Repository\UserRepository;
use LocalheroPortal\Models\User\User;

class ExpertLeadController extends Controller
{
    public function index(User $expert)
    {
        return view('experts.leads.list', compact('expert'));
    }

    //TODO: move to CreateLeadController?
    public function create(User $expert)
    {
        $leadRepo = new LeadRepository();
        $allCategories = $leadRepo->getAllCategoriesAsString();
        $allExperts = collect(UserRepository::getAllExperts())->filter(fn($c) => $c['last_name'] != null)->values();
        return view('experts.leads.CreateLeadView', ['expert' => $expert, 'allCategories' => $allCategories, 'allExperts' => $allExperts]);
    }
}
