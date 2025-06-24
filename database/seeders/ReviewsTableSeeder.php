<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Store;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            'منتجات ممتازة وخدمة رائعة.',
            'تجربة تسوق مميزة وسعيد بالمنتجات التي اشتريتها.',
            'سرعة في التوصيل وجودة عالية.',
            'أسعار معقولة مقارنة بالمتاجر الأخرى.',
            'المنتجات مطابقة للوصف وصاحب المتجر متعاون.',
            'تجربة رائعة وسأكرر الشراء بالتأكيد.',
            'المنتجات جيدة ولكن التوصيل كان متأخراً قليلاً.',
            'جودة المنتجات ممتازة وأنصح بالشراء من هذا المتجر.',
            'تجربة مرضية بشكل عام.',
            'خدمة عملاء متميزة وتعامل راقي.'
        ];
        
        $users = User::where('role', 'user')->get();
        $stores = Store::all();
        
        foreach ($users as $user) {
            $randomStores = $stores->random(rand(3, 8));
            
            foreach ($randomStores as $store) {
                Review::create([
                    'user_id' => $user->id,
                    'store_id' => $store->id,
                    'rating' => rand(1, 5),
                    'comment' => $comments[array_rand($comments)]
                ]);
            }
        }
    }
}