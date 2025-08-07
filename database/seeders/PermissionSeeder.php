<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user.index',
            'user.create',
            'user.edit',
            'user.destroy',

            'role.index',
            'role.create',
            'role.edit',
            'role.destroy',

            'permission.index',
            'permission.create',
            'permission.edit',
            'permission.destroy',

            'categoria.index',
            'categoria.create',
            'categoria.edit',
            'categoria.destroy',

            'producto.index',
            'producto.create',
            'producto.edit',
            'producto.destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        //Asignar todos los permisos al rol admin
        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo($permissions);

        //Asignar permisos al rol editor
        $role = Role::where('name', 'editor')->first();
        $role->givePermissionTo([
            'categoria.index',
            'categoria.create',
            'categoria.edit',
            'producto.index',
            'producto.create',
            'producto.edit',
        ]);

        //Asignar permisos al rol user
        $role = Role::where('name', 'user')->first();
        $role->givePermissionTo([
            'categoria.index',
            'producto.index',
        ]);
    }
}
