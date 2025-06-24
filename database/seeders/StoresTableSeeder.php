<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\Category;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة', 'الطائف', 'تبوك', 'حائل', 'أبها'];
        $storeNames = [
            'متجر الأصالة', 'متجر النخبة', 'متجر الصفوة', 'متجر الإبداع',
            'متجر الريادة', 'متجر الفخامة', 'متجر النجاح', 'متجر التميز',
            'متجر الأفق', 'متجر السعادة', 'متجر الرونق', 'متجر الأناقة'
        ];
        
        $categories = Category::all();
        
        $vendors = User::where('role', 'vendor')->get();
        
        $storeIndex = 0;
        foreach ($vendors as $vendor) {
            for ($i = 1; $i <= 4; $i++) {
                Store::create([
                    'user_id' => $vendor->id,
                    'name' => $storeNames[$storeIndex],
                    'description' => 'وصف مفصل عن متجر ' . $storeNames[$storeIndex] . ' ومنتجاته وخدماته المميزة.',
                    'contact_info' => '05' . rand(10000000, 99999999),
                    'city' => $cities[array_rand($cities)],
                    'status' => 'approved',
                    'category_id' => $categories->random()->id
                ]);
                
                $storeIndex++;
            }
        }
    }
}