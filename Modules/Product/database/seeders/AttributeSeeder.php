<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Attributes
        \Illuminate\Support\Facades\DB::table('attributes')->insert([
            ['id' => 1, 'name' => 'Size', 'slug' => 'size', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Color', 'slug' => 'color', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Material', 'slug' => 'material', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Brand', 'slug' => 'brand', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Fit', 'slug' => 'fit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Pattern', 'slug' => 'pattern', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert Attribute Values
        \Illuminate\Support\Facades\DB::table('attribute_values')->insert([
            // Size values (id 1-15) - Adult sizes
            ['id' => 1, 'attribute_id' => 1, 'value' => 'XS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'attribute_id' => 1, 'value' => 'S', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'attribute_id' => 1, 'value' => 'M', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'attribute_id' => 1, 'value' => 'L', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'attribute_id' => 1, 'value' => 'XL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'attribute_id' => 1, 'value' => 'XXL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'attribute_id' => 1, 'value' => '28', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'attribute_id' => 1, 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'attribute_id' => 1, 'value' => '32', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'attribute_id' => 1, 'value' => '34', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'attribute_id' => 1, 'value' => '36', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'attribute_id' => 1, 'value' => '6', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'attribute_id' => 1, 'value' => '7', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'attribute_id' => 1, 'value' => '8', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'attribute_id' => 1, 'value' => '9', 'created_at' => now(), 'updated_at' => now()],

            // Kids age-based size values (id 58-67)
            ['id' => 58, 'attribute_id' => 1, 'value' => '2-3Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 59, 'attribute_id' => 1, 'value' => '3-4Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 60, 'attribute_id' => 1, 'value' => '4-5Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 61, 'attribute_id' => 1, 'value' => '5-6Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 62, 'attribute_id' => 1, 'value' => '6-7Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 63, 'attribute_id' => 1, 'value' => '7-8Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 64, 'attribute_id' => 1, 'value' => '8-9Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 65, 'attribute_id' => 1, 'value' => '9-10Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 66, 'attribute_id' => 1, 'value' => '10-11Y', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 67, 'attribute_id' => 1, 'value' => '11-12Y', 'created_at' => now(), 'updated_at' => now()],

            // Color values (id 16-25)
            ['id' => 16, 'attribute_id' => 2, 'value' => 'Black', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'attribute_id' => 2, 'value' => 'White', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'attribute_id' => 2, 'value' => 'Blue', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'attribute_id' => 2, 'value' => 'Red', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'attribute_id' => 2, 'value' => 'Green', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'attribute_id' => 2, 'value' => 'Navy', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'attribute_id' => 2, 'value' => 'Gray', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'attribute_id' => 2, 'value' => 'Pink', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'attribute_id' => 2, 'value' => 'Yellow', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'attribute_id' => 2, 'value' => 'Multicolor', 'created_at' => now(), 'updated_at' => now()],

            // Material values (id 26-35)
            ['id' => 26, 'attribute_id' => 3, 'value' => 'Cotton', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'attribute_id' => 3, 'value' => 'Polyester', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'attribute_id' => 3, 'value' => 'Denim', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'attribute_id' => 3, 'value' => 'Leather', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'attribute_id' => 3, 'value' => 'Silk', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'attribute_id' => 3, 'value' => 'Chiffon', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'attribute_id' => 3, 'value' => 'Synthetic', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'attribute_id' => 3, 'value' => 'Wool', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'attribute_id' => 3, 'value' => 'Rubber', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'attribute_id' => 3, 'value' => 'Mesh', 'created_at' => now(), 'updated_at' => now()],

            // Brand values (id 36-45)
            ['id' => 36, 'attribute_id' => 4, 'value' => 'StyleHub', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'attribute_id' => 4, 'value' => 'FashionPro', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'attribute_id' => 4, 'value' => 'TrendWear', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'attribute_id' => 4, 'value' => 'UrbanStyle', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'attribute_id' => 4, 'value' => 'ClassicWear', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'attribute_id' => 4, 'value' => 'SportMax', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'attribute_id' => 4, 'value' => 'KidsJoy', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'attribute_id' => 4, 'value' => 'TechGear', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'attribute_id' => 4, 'value' => 'LuxeStyle', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'attribute_id' => 4, 'value' => 'ComfortZone', 'created_at' => now(), 'updated_at' => now()],

            // Fit values (id 46-50)
            ['id' => 46, 'attribute_id' => 5, 'value' => 'Slim Fit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'attribute_id' => 5, 'value' => 'Regular Fit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'attribute_id' => 5, 'value' => 'Loose Fit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'attribute_id' => 5, 'value' => 'Skinny Fit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'attribute_id' => 5, 'value' => 'Relaxed Fit', 'created_at' => now(), 'updated_at' => now()],

            // Pattern values (id 51-57)
            ['id' => 51, 'attribute_id' => 6, 'value' => 'Solid', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'attribute_id' => 6, 'value' => 'Printed', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 53, 'attribute_id' => 6, 'value' => 'Striped', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'attribute_id' => 6, 'value' => 'Checkered', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 55, 'attribute_id' => 6, 'value' => 'Floral', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 56, 'attribute_id' => 6, 'value' => 'Plain', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 57, 'attribute_id' => 6, 'value' => 'Graphic', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
