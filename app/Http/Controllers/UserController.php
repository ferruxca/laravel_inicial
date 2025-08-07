<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);

        return view('pages.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('pages.users.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'role' => 'required',
        ]);
        $validated['password'] = bcrypt($validated['password']); // Encriptar la contraseña

        $user = User::create($validated);
        $role = Role::find($request->role);
        $user->syncRoles($role);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $current_role = $user->getRoleNames()->first();
        $role = Role::where('name', $current_role)->first();

        return view('pages.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'current_role' => $role->id,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required',
            'role' => 'required',
        ];

        // Si el password no cambio, no se actualiza
        if ($request->password == '') {
            $request->offsetUnset('password');
            $rules['password'] = 'nullable';
            $rules['password_confirmation'] = 'nullable';
        }

        // Si el email no cambio, no se actualiza
        if ($request->email == $user->email) {
            $request->offsetUnset('email');
            $rules['email'] = 'nullable';
        }

        // El usuario logueado no puede cambiar su rol
        if($user->id == auth()->user()->id && $request->role != $user->getRoleNames()->first()){
            $role_id = Role::findByName( $user->getRoleNames()->first())->id;
            $request['role'] = $role_id;
        }

        $validated = $request->validate($rules);

        $user->update($validated);
        $role = Role::find($request->role);
        $user->syncRoles($role);
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if($user->id == auth()->user()->id){
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
