<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        if (!auth()->user()->can('role.index')) {
            abort(403);
        }
        
        
        $roles = Role::with('permissions')->paginate(10);
        $permissions = Permission::all()->groupBy('group');
        
        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        if (!auth()->user()->can('role.create')) {
            abort(403);
        }
        
        $permissions = Permission::all()->groupBy('group');
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('role.create')) {
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id' // Valida que cada permiso exista
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create(['name' => $request->name]);
        
        if ($request->filled('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        if (!auth()->user()->can('role.edit')) {
            abort(403);
        }
        
        $permissions = Permission::all()->groupBy('group');
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->can('role.edit')) {
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id' // Valida que cada permiso exista
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update(['name' => $request->name]);
        
        // Sincronizar permisos usando nombres en lugar de IDs
        if ($request->filled('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if (!auth()->user()->can('role.destroy')) {
            abort(403);
        }
        
        $role->delete();
        
        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}