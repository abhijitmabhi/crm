<?php

namespace LocalheroPortal\Core\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Http\Resources\UserResource;
use LocalheroPortal\Models\User\Role;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class UserController extends Controller
{
    /**
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 40;
        $users = User::query()
            ->byRole(RoleType::EXPERT)
            ->byNotRole(RoleType::CITY)
            ->where('is_active', '=', 1);
        if ($request->callagent) {
            $callagent = User::find($request->callagent);
            $users->byCallagent($callagent);
        }
        if ($request->name) {
            $users->where(function (Builder $query) use ($request) {
                return $query
                    ->orWhere('last_name', 'LIKE', "%$request->name%")
                    ->orWhere('first_name', 'LIKE', "%$request->name%");
            });
        }
        if ($request->role) {
            if (is_numeric($request->role)) {
                $role = Role::find($request->role)->first();
                if ($role) {
                    $role = $role->name;
                }
            } else {
                $role = $request->role;
            }
            if ($role) {
                $users->byRole($role);
            }
        }
        if ($request->searchterm) {
            $users->search($request->searchterm);
        }
        if ($request->sort_direction) {
            $sort_direction = "DESC" === strtoupper($request->sort_direction) ? "DESC" : "ASC";
            $users->orderBy('name', $sort_direction);
        }
        $sort_direction = "DESC" === strtoupper($request->sort_direction) ? "DESC" : "ASC";
        $users->orderBy('last_name', $sort_direction);
        return new UserResource($users->paginate($per_page));
    }

    /**
     * @param User $user
     */
    public function show(User $user)
    {
        return Response::json($user->toArray());
    }

}
