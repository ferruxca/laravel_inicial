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

        // Creamos el usuario
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin@admin.com',
            'password' => '12345678',
        ]);

        // Asignamos el rol al usuario
        $user->assignRole('admin');
    }
}
