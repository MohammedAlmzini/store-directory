<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Store;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $stores = Store::all();
        
        foreach ($users as $user) {
            $randomStores = $stores->random(rand(2, 6));
            
            foreach ($randomStores as $store) {
                Favorite::create([
                    'user_id' => $user->id,
                    'store_id' => $store->id
                ]);
            }
        }
    }
}