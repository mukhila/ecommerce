<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KidsProductSizeSeeder extends Seeder
{
    /**
     * Seed kids age-based size attribute values.
     *
     * This seeder adds age-based sizes (2-3Y, 3-4Y, etc.) for children's clothing
     * and optionally attaches them to specified products.
     */
    public function run(): void
    {
        // Get or create the Size attribute
        $sizeAttribute = DB::table('attributes')->where('slug', 'size')->first();

        if (!$sizeAttribute) {
            $sizeAttributeId = DB::table('attributes')->insertGetId([
                'name' => 'Size',
                'slug' => 'size',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $sizeAttributeId = $sizeAttribute->id;
        }

        // Kids age-based sizes
        $kidsSizes = [
            '2-3Y',
            '3-4Y',
            '4-5Y',
            '5-6Y',
            '6-7Y',
            '7-8Y',
            '8-9Y',
            '9-10Y',
            '10-11Y',
            '11-12Y',
        ];

        $insertedSizeIds = [];

        foreach ($kidsSizes as $size) {
            // Check if size already exists
            $existingValue = DB::table('attribute_values')
                ->where('attribute_id', $sizeAttributeId)
                ->where('value', $size)
                ->first();

            if (!$existingValue) {
                $sizeId = DB::table('attribute_values')->insertGetId([
                    'attribute_id' => $sizeAttributeId,
                    'value' => $size,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $insertedSizeIds[$size] = $sizeId;
            } else {
                $insertedSizeIds[$size] = $existingValue->id;
            }
        }

        $this->command->info('Kids size attribute values seeded successfully!');
        $this->command->info('Available sizes: ' . implode(', ', $kidsSizes));

        // Optionally attach to kids products (categories 12 and 13 are kids)
        // You can specify product IDs here if needed
        $kidsProductIds = DB::table('products')
            ->whereIn('category_id', [12, 13]) // Kids Boys (12) and Kids Girls (13)
            ->pluck('id')
            ->toArray();

        if (!empty($kidsProductIds)) {
            $this->command->info('Found ' . count($kidsProductIds) . ' kids products.');

            // Attach first 4 sizes (2-3Y to 5-6Y) to each kids product
            $sizesToAttach = ['2-3Y', '3-4Y', '4-5Y', '5-6Y'];

            foreach ($kidsProductIds as $productId) {
                // First, remove existing Size attributes for this product
                $existingSizeAttributeValueIds = DB::table('product_attributes')
                    ->where('product_id', $productId)
                    ->where('attribute_id', $sizeAttributeId)
                    ->pluck('attribute_value_id')
                    ->toArray();

                // Add the new kids sizes
                foreach ($sizesToAttach as $index => $sizeValue) {
                    $attributeValueId = $insertedSizeIds[$sizeValue];

                    // Check if already exists
                    $exists = DB::table('product_attributes')
                        ->where('product_id', $productId)
                        ->where('attribute_value_id', $attributeValueId)
                        ->exists();

                    if (!$exists) {
                        DB::table('product_attributes')->insert([
                            'product_id' => $productId,
                            'attribute_id' => $sizeAttributeId,
                            'attribute_value_id' => $attributeValueId,
                            'stock' => rand(10, 50), // Random stock between 10-50
                            'price' => null, // Use product's default price
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            $this->command->info('Kids sizes attached to ' . count($kidsProductIds) . ' products.');
        }
    }
}
