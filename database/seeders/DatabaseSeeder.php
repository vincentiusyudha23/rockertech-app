<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Esp32Mode;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        Role::updateOrCreate(['name' => 'admin'],['name' => 'admin']);
        Role::updateOrCreate(['name' => 'employee'],['name' => 'employee']);

        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => Hash::make('admin')
        ]);

        // $ceo = \App\Models\User::factory()->create([
        //     'name' => 'Nando Ario Febriyansah',
        //     'username' => 'nando',
        //     'email' => 'nando@test.com',
        //     'role' => '12345678',
        //     'password' => Hash::make('12345678')
        // ]);

        $admin->assignRole('admin');

        Esp32Mode::setPrecense()->setRegis()->setOffRegis();
    }
}
