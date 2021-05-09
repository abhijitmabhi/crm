<?php
use LocalheroPortal\Models\User\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}.notifications', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}.calendar', function ($user, $id) {
    return true;
});

Broadcast::channel('companies.{id}', function ($user, $id) {
    /**
     * @var User $user
     */
    if ($user->company) {
        return (int) $user->company->id === (int) $id;
    }
    return $user->can('manage-company');
});