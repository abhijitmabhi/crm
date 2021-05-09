<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('ChangelogView');
    }
}
