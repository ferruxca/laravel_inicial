<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{

    /**
     * Display a listing of permissions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!auth()->user()->can('permission.index')) {
            abort(403);
        }

        $permissions = Permission::orderBy('name')
            ->paginate(10);

        return view('permissions.index', [
            'permissions' => $permissions,
            //'groups' => Permission::distinct()->pluck('group')
        ]);
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!auth()->user()->can('permission.create')) {
            abort(403);
        }
        
        return view('permissions.create', [
            //'groups' => Permission::distinct()->pluck('group')
        ]);
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('permission.create')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name|max:255',
            'group' => 'required|string|max:255',
        ], [
            'name.unique' => __('A permission with this name already exists.'),
            'group.required' => __('The group field is required.'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => 'web' // Asegurar que se establezca el guard_name
        ]);

        return redirect()->route('permissions.index')
            ->with('success', __('Permission created successfully.'));
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  Permission  $permission
     * @return \Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        if (!auth()->user()->can('permission.edit')) {
            abort(403);
        }

        return view('permissions.edit', [
            'permission' => $permission,
            //'groups' => Permission::distinct()->pluck('group')
        ]);
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Permission $permission)
    {
        if (!auth()->user()->can('permission.edit')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'group' => 'required|string|max:255',
        ], [
            'name.unique' => __('A permission with this name already exists.'),
            'group.required' => __('The group field is required.'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission->update([
            'name' => $request->name,
            'group' => $request->group
        ]);

        return redirect()->route('permissions.index')
            ->with('success', __('Permission updated successfully.'));
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Permission $permission)
    {
        if (!auth()->user()->can('permission.delete')) {
            abort(403);
        }

        // Verificar si el permiso está siendo usado antes de eliminar
        if ($permission->roles()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete permission: it is assigned to one or more roles.'));
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', __('Permission deleted successfully.'));
    }
}