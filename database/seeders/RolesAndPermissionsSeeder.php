<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['USER', 'ADMIN', 'CLIENTE', 'EMPLE'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        //Crear rol user
        $user=User::create([
            'name' => 'rodrigo',
            'email' => 'rodrigo@gmail.com',
            'password' => 'rodrigo',
            'puntos' => 100,
        ]);
        $user->assignRole('USER');

        // Crear un administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('castelar'),
            'puntos' => 1000, //El admin empieza con más puntos
        ]);
        $admin->assignRole('ADMIN');

    }
}
