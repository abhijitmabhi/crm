<?php

namespace LocalheroPortal\LLI\Feature\Settings;

use LocalheroPortal\Core\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function getView()
    {
        return view('lli.settings.SettingsView');
    }
}
