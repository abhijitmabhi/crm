<?php

namespace LocalheroPortal\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use LocalheroPortal\Models\User\Role;

class RoleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        return view('roles.CreateRoleView');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role                                $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $this->authorize('destroy', Role::class);
        $role->delete();

        return redirect(route('roles.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role       $role
     * @return Response
     */
    public function edit(Role $role)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('index', Role::class);
        $roles = Role::paginate(15);

        return view('roles.RoleTableView', ['roles' => $roles]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Role                    $role
     * @return \Illuminate\View\View
     */
    public function show(Role $role)
    {
        return view('roles.EditRoleView', ['role' => $role]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request                             $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('store', Role::class);
        $role = new Role($request->all());
        $role->save();

        return redirect(route('roles.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request                             $request
     * @param  Role                                $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        $role->name = $request->name ?? $role->name;
        $role->display_name = $request->display_name ?? $role->display_name;
        $role->permissions = $request->permissions ?? $role->permissions;
        $role->save();
        Cache::tags('permissions')->flush();

        return redirect(route('roles.index'));
    }
}