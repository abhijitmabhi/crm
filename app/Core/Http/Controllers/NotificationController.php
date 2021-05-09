<?php
namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\User\User;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->get_parsed_notifications();

        return view('notifications.index', $notifications);
    }
}
