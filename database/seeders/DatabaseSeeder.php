<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);
        DB::table('users')->insert([[
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'code' => '123456',
            'rol' => 1, // Suponiendo que el ID del rol de administrador es 1
            'remember_token' => Str::random(10),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Usuario1',
            'email' => 'usuario1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password1'),
            'code' => '111111',
            'rol' => 3, // Suponiendo que el ID del rol de usuario es 2
            'remember_token' => Str::random(10),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Usuario2',
            'email' => 'usuario2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'code' => '222222',
            'rol' => 2, // Suponiendo que el ID del rol de usuario es 2
            'remember_token' => Str::random(10),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    DB::table('category')->insert([
        ['name' => 'Electrónicos'],
        ['name' => 'Ropa'],
        ['name' => 'Hogar'],
    ]);
    DB::table('products')->insert([
        [
            'name' => 'Laptop',
            'precio' => 1299.99,
            'category_id' => 1, // Asignar a la categoría con ID 1 (Electrónicos)
            'status' => true,
        ],
        [
            'name' => 'Sofá de cuero',
            'precio' => 799.99,
            'category_id' => 3, // Asignar a la categoría con ID 3 (Hogar)
            'status' => true,
        ],
        [
            'name' => 'Zapatos deportivos',
            'precio' => 59.99,
            'category_id' => 2, // Asignar a la categoría con ID 2 (Ropa)
            'status' => true,
        ],
    ]);
        
        // \App\Models\User::factory(10)->create();
        
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
