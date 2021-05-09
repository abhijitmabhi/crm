<?php

namespace LocalheroPortal\Core\Http\Controllers;

class OfflineController extends Controller
{
    public function __invoke()
    {
        return view('home');
    }
}
