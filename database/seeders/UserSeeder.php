<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'administrador@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Doctor',
            'email' => 'doctor@gmail.com',
            'password' => Hash::make('doctor'),
            'role' => 'doctor',
        ]);
        User::create([
            'name' => 'Recepcionista',
            'email' => 'recepcionista@gmail.com',
            'password' => Hash::make('recepcionista'),
            'role' => 'recepcionist',
        ]);
    }
}
