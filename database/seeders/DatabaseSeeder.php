<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creamos los roles y permisos
        $this->call([
            RolesSeeder::class,
            PermissionSeeder::class,
        ]);

        // Creamos tres usuarios        
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin@admin.com',
            'password' => '12345678',
        ]);

        $editor = User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@editor.com',
            'password' => '12345678',
        ]);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => '12345678',
        ]);

        // Asignamos roles a los usuarios
        $admin->assignRole('admin');
        $editor->assignRole('editor');
        $user->assignRole('user');
    }
}
