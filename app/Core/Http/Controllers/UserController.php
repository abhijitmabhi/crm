<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use LocalheroPortal\Models\User\Role;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;


class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $all_roles = Role::all();
        return view('users.CreateUserView', ['all_roles' => $all_roles]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        return redirect('/users/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', User::class);
        $users = User::query()
            ->with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'customer');
            })->get();
        return view('users.UserTableView', ['users' => $users]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     * @return View
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('show', $user);
        $all_roles = Role::all();
        $user_roles = $user->roles()->get();

        return view('users.EditUserView', ['user' => $user, 'user_roles' => $user_roles, 'all_roles' => $all_roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector|void
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('store', $this);
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3|max:20',
            'lastName' => 'required|min:3|max:60',
            'email' => 'required|unique:users,email|',
            'password' => 'required|required_with:password_confirmation|confirmed|min:8',
            'roles' => 'required|distinct|min:1',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            abort(400, $error);
            return;
        }
        $data = [
            'email' => $request['email'],
            'name' => $request['firstName'] . ' ' . $request['lastName'],
            'password' => Hash::make($request['password']),
        ];
        $permissions = $request->input('roles');
        $roleIds = array();

        foreach ($permissions as $permission) {
            array_push($roleIds, $permission['id']);
        }

        $user = new User($data);
        $userSaved = $user->save();
        $savedRoles = $user->roles()->sync($roleIds);

        if (!$userSaved || !$savedRoles) {
            abort(400, 'Anlegen des Users hat nicht funktioniert.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return void
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        if (Auth::id() != $user->id) {
            $this->authorize('update', $user);
        }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3|max:20',
            'lastName' => 'required|min:3|max:60',
            'email' => 'required:users,email',
            'password' => 'nullable|confirmed|min:8',
            'roles' => [
                Rule::requiredIf(!Auth::user()->hasRole(RoleType::CUSTOMER)),
                'distinct',
                'min:1'
            ]
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            abort(400, $error);
        }
        $newEmail = $request->email ?? $user->email;
        if ($user->email != $newEmail) {
            $conflictUser = User::whereEmail($newEmail)->first();
            if ($conflictUser) {
                abort(409, 'Email Adresse existiert bereits.');
            }
        }

        if ($request->roles && !Auth::user()->hasRole(RoleType::CUSTOMER)) {
            $roleIds = collect($request->roles)->map(function ($role) {
                return $role['id'];
            });
            $user->roles()->sync($roleIds->toArray());
        }
        $totalName = $request->firstName . ' ' . $request->lastName;
        $user->name = $totalName;
        $user->email = $newEmail;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;
        $user->save();
    }
}
