<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        if (!auth()->user()->can('permission.index')) {
            abort(403);
        }

        $search = $request->get('search');
        $permissions = Permission::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->query());

        return view('pages.permissions.index', [
            'permissions' => $permissions,
            'search' => $search,
        ]);
    }

    public function create()
    {
        if (!auth()->user()->can('permission.create')) {
            abort(403);
        }
        
        return view('pages.permissions.create', [
            //'groups' => Permission::distinct()->pluck('group')
        ]);
    }

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

        $permission = Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => 'web' // Asegurar que se establezca el guard_name
        ]);

        // Asignamos el permiso al rol admin
        $role = Role::find(1);
        $role->givePermissionTo($permission);

        return redirect()->route('permissions.index')
            ->with('success', __('Permission created successfully.'));
    }

    public function edit(Permission $permission)
    {
        if (!auth()->user()->can('permission.edit')) {
            abort(403);
        }

        return view('pages.permissions.edit', [
            'permission' => $permission,
            //'groups' => Permission::distinct()->pluck('group')
        ]);
    }

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