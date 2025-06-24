<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'إلكترونيات',
            'ملابس',
            'أثاث',
            'مستلزمات منزلية',
            'مواد غذائية',
            'مستحضرات تجميل',
            'أدوات مكتبية',
            'ألعاب',
            'رياضة',
            'كتب'
        ];
        
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}