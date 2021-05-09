<?php

namespace LocalheroPortal\Core\Http\Controllers;

use LocalheroPortal\Core\Repository\LeadRepository;

class LeadCategoryStatsController extends Controller
{

    public function getLeadCategoryStats()
    {
        $repo = new LeadRepository();
        $viewData = $repo->countByStatusGroupByCategories();
        return view('leads.LeadCategoryStatsView', ['data' => $viewData]);
    }
}
