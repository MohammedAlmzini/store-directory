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
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
     ]);
     
     User::create([
    'name' => 'Vendor One',
    'email' => 'vendor@example.com',
    'password' => Hash::make('password'),
    'role' => 'vendor',
     ]);
    
    User::create([
    'name' => 'Normal User',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'user',
     ]);

    }
}
