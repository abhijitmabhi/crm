<?php


namespace LocalheroPortal\Core\Http\Controllers\Api;


use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\LeadRepository;

class CategoryController extends Controller
{

    public function getAllLeadsCategoriesAsString()
    {
        $leadRepo = new LeadRepository();
        return $leadRepo->getAllCategoriesAsString();
    }

    public function getAllLeadsCategoriesAsObject()
    {
        $leadRepo = new LeadRepository();
        return $leadRepo->getAllCategoriesAsObject();
    }
}