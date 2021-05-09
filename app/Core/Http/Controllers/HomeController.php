<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\User\RoleType;

class HomeController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $isDataScraper = $user->hasRole(RoleType::LLI_DATA_SCRAPER);

        if($isDataScraper) {
            return redirect()->route('lli-data-scraping');
        } else {
            return view('home');
        }

    }
}
