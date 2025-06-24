<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'mamzani@smail.ucas.edu.ps',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
        
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => 'Vendor ' . $i,
                'email' => 'vendor' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'vendor',
            ]);
        }
        
        for ($i = 1; $i <= 7; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }
    }
}