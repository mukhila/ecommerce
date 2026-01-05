<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('categories')->insert([
            // Men's Fashion
            ['id' => 1, 'name' => 'Men', 'slug' => 'men', 'parent_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'T-Shirts', 'slug' => 'mens-tshirts', 'parent_id' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Shirts', 'slug' => 'mens-shirts', 'parent_id' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Jeans', 'slug' => 'mens-jeans', 'parent_id' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Shoes', 'slug' => 'mens-shoes', 'parent_id' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Women's Fashion
            ['id' => 6, 'name' => 'Women', 'slug' => 'women', 'parent_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Dresses', 'slug' => 'womens-dresses', 'parent_id' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Tops', 'slug' => 'womens-tops', 'parent_id' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Jeans', 'slug' => 'womens-jeans', 'parent_id' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Heels', 'slug' => 'womens-heels', 'parent_id' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Kids
            ['id' => 11, 'name' => 'Kids', 'slug' => 'kids', 'parent_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Boys Clothing', 'slug' => 'boys-clothing', 'parent_id' => 11, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Girls Clothing', 'slug' => 'girls-clothing', 'parent_id' => 11, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Electronics
            ['id' => 14, 'name' => 'Electronics', 'slug' => 'electronics', 'parent_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'Smartphones', 'slug' => 'smartphones', 'parent_id' => 14, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Headphones', 'slug' => 'headphones', 'parent_id' => 14, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Accessories
            ['id' => 17, 'name' => 'Accessories', 'slug' => 'accessories', 'parent_id' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Bags', 'slug' => 'bags', 'parent_id' => 17, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'name' => 'Watches', 'slug' => 'watches', 'parent_id' => 17, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
