<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class CalendarController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole(RoleType::EXPERT)) {
            $agentMode = !(1 == $user->roles->count() || (2 === $user->roles->count() && $user->hasRole(RoleType::CALL_CENTER_AGENT)));
        } else {
            $agentMode = true;
        }
        $users = User::select('id', 'first_name', 'last_name')->get();
        $user = $request->input('user') == null ? Auth::user() : User::find((int) $request->input('user'));
        return view('CalendarView', compact('users', 'user', 'agentMode'));
    }
}
