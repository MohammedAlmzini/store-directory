<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productNames = [
            'هاتف ذكي', 'حاسوب محمول', 'شاشة', 'سماعات', 'قميص', 'بنطال', 'حذاء', 'حقيبة',
            'سرير', 'طاولة', 'كرسي', 'خزانة', 'مصباح', 'سجادة', 'ستارة', 'وعاء',
            'مكنسة كهربائية', 'غسالة', 'ثلاجة', 'فرن', 'كتاب', 'قلم', 'دفتر', 'آلة حاسبة',
            'كرة قدم', 'مضرب تنس', 'دراجة هوائية', 'حزام', 'نظارة', 'ساعة'
        ];
        
        $categories = Category::all();
        $stores = Store::all();
        
        foreach ($stores as $store) {
            for ($i = 1; $i <= 20; $i++) {
                Product::create([
                    'store_id' => $store->id,
                    'category_id' => $categories->random()->id,
                    'name' => $productNames[array_rand($productNames)] . ' ' . rand(1, 100),
                    'price' => rand(50, 5000) / 10,
                    'description' => 'وصف تفصيلي للمنتج وخصائصه ومميزاته وطريقة استخدامه.'
                ]);
            }
        }
    }
}