<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de importar el modelo User

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@editorial.com',
            'password' => Hash::make('admin123'), // contraseña segura
            'role' => 'admin',
        ]);

        // Usuario editor
        User::create([
            'name' => 'Editor',
            'email' => 'editor@editorial.com',
            'password' => Hash::make('editor123'),
            'role' => 'editor',
        ]);
    }
}
