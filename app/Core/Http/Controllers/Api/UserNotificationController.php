<?php
namespace LocalheroPortal\Core\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class UserNotificationController extends Controller
{
    /**
     * @param User    $user
     * @param Request $request
     */
    public function index(User $user, Request $request): JsonResponse
    {
        return Response::json($user->get_parsed_notifications());
    }

    /**
     * @param User $user
     */
    public function readAll(User $user)
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * @param User                 $user
     * @param DatabaseNotification $notification
     */
    public function show(User $user, DatabaseNotification $notification): JsonResponse
    {
        return Response::json($notification->toArray());
    }

    /**
     * @param User                 $user
     * @param DatabaseNotification $notification
     */
    public function update(User $user, DatabaseNotification $notification): void
    {
        $notification->markAsRead();
    }
}
