<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'expense' => ['food', 'groceries', 'transport', 'taxi', 'health', 'tobacco', 'entertainment', 'subscriptions', 'clothes', 'home', 'education', 'family', 'debt', 'other'],
            'income' => ['salary', 'freelance', 'order', 'gift', 'trading', 'business', 'other'],
            'task' => ['personal', 'work', 'study', 'finance', 'health', 'family', 'other'],
        ];

        foreach ($data as $type => $slugs) {
            foreach ($slugs as $slug) {
                Category::query()->updateOrCreate(
                    ['user_id' => null, 'type' => $type, 'slug' => $slug],
                    ['name_ru' => $slug, 'name_en' => $slug, 'is_system' => true],
                );
            }
        }
    }
}
