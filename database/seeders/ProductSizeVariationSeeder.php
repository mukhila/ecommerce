<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Adds size variations (with stock and price) to existing products
     */
    public function run(): void
    {
        // Size attribute_id = 1
        // Adult sizes: XS=1, S=2, M=3, L=4, XL=5, XXL=6
        // Numeric sizes: 28=7, 30=8, 32=9, 34=10, 36=11
        // Shoe sizes: 6=12, 7=13, 8=14, 9=15
        // Kids sizes: 2-3Y=58, 3-4Y=59, 4-5Y=60, 5-6Y=61, 6-7Y=62, 7-8Y=63, 8-9Y=64, 9-10Y=65

        $sizeAttributeId = 1;

        // First, remove existing size variations (only size attribute)
        DB::table('product_attributes')
            ->where('attribute_id', $sizeAttributeId)
            ->delete();

        $productSizeVariations = [];

        // === MEN'S T-SHIRTS (Category 2) - Adult letter sizes ===

        // Product 3: Classic Cotton T-Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(3, [
            ['value_id' => 1, 'stock' => 10, 'price' => null],      // XS
            ['value_id' => 2, 'stock' => 25, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 30, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 25, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 15, 'price' => null],      // XL
            ['value_id' => 6, 'stock' => 8, 'price' => 499.00],     // XXL - higher price
        ]));

        // Product 4: Graphic Print T-Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(4, [
            ['value_id' => 2, 'stock' => 20, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 35, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 30, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 20, 'price' => null],      // XL
            ['value_id' => 6, 'stock' => 10, 'price' => 849.00],    // XXL - higher price
        ]));

        // Product 5: V-Neck T-Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(5, [
            ['value_id' => 1, 'stock' => 15, 'price' => null],      // XS
            ['value_id' => 2, 'stock' => 20, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 25, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 20, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 12, 'price' => null],      // XL
        ]));

        // === MEN'S SHIRTS (Category 3) ===

        // Product 6: Formal White Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(6, [
            ['value_id' => 2, 'stock' => 12, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 20, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 25, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 15, 'price' => null],      // XL
            ['value_id' => 6, 'stock' => 8, 'price' => 1099.00],    // XXL
        ]));

        // Product 7: Casual Denim Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(7, [
            ['value_id' => 2, 'stock' => 10, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 18, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 22, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 15, 'price' => null],      // XL
        ]));

        // Product 8: Checkered Casual Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(8, [
            ['value_id' => 2, 'stock' => 15, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 25, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 20, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 12, 'price' => null],      // XL
        ]));

        // === MEN'S JEANS (Category 4) - Numeric sizes ===

        // Product 9: Slim Fit Blue Jeans
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(9, [
            ['value_id' => 7, 'stock' => 10, 'price' => null],      // 28
            ['value_id' => 8, 'stock' => 18, 'price' => null],      // 30
            ['value_id' => 9, 'stock' => 25, 'price' => null],      // 32
            ['value_id' => 10, 'stock' => 20, 'price' => null],     // 34
            ['value_id' => 11, 'stock' => 12, 'price' => 1899.00],  // 36
        ]));

        // Product 10: Black Skinny Jeans
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(10, [
            ['value_id' => 7, 'stock' => 8, 'price' => null],       // 28
            ['value_id' => 8, 'stock' => 15, 'price' => null],      // 30
            ['value_id' => 9, 'stock' => 20, 'price' => null],      // 32
            ['value_id' => 10, 'stock' => 15, 'price' => null],     // 34
        ]));

        // === MEN'S SHOES (Category 5) - Shoe sizes ===

        // Product 11: Leather Formal Shoes
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(11, [
            ['value_id' => 12, 'stock' => 5, 'price' => null],      // 6
            ['value_id' => 13, 'stock' => 8, 'price' => null],      // 7
            ['value_id' => 14, 'stock' => 12, 'price' => null],     // 8
            ['value_id' => 15, 'stock' => 10, 'price' => null],     // 9
        ]));

        // Product 12: Sports Running Shoes
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(12, [
            ['value_id' => 12, 'stock' => 8, 'price' => null],      // 6
            ['value_id' => 13, 'stock' => 12, 'price' => null],     // 7
            ['value_id' => 14, 'stock' => 15, 'price' => null],     // 8
            ['value_id' => 15, 'stock' => 12, 'price' => null],     // 9
        ]));

        // === WOMEN'S DRESSES (Category 7) ===

        // Product 13: Floral Summer Dress
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(13, [
            ['value_id' => 1, 'stock' => 8, 'price' => null],       // XS
            ['value_id' => 2, 'stock' => 15, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 20, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 15, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 8, 'price' => 1699.00],    // XL
        ]));

        // Product 14: Elegant Evening Dress
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(14, [
            ['value_id' => 1, 'stock' => 5, 'price' => null],       // XS
            ['value_id' => 2, 'stock' => 10, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 12, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 10, 'price' => null],      // L
        ]));

        // Product 15: Casual Midi Dress
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(15, [
            ['value_id' => 1, 'stock' => 10, 'price' => null],      // XS
            ['value_id' => 2, 'stock' => 18, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 22, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 15, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 8, 'price' => null],       // XL
        ]));

        // === WOMEN'S TOPS (Category 8) ===

        // Product 16: Sleeveless Casual Top
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(16, [
            ['value_id' => 1, 'stock' => 15, 'price' => null],      // XS
            ['value_id' => 2, 'stock' => 25, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 30, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 20, 'price' => null],      // L
            ['value_id' => 5, 'stock' => 10, 'price' => null],      // XL
        ]));

        // Product 17: Printed Crop Top
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(17, [
            ['value_id' => 1, 'stock' => 18, 'price' => null],      // XS
            ['value_id' => 2, 'stock' => 28, 'price' => null],      // S
            ['value_id' => 3, 'stock' => 25, 'price' => null],      // M
            ['value_id' => 4, 'stock' => 15, 'price' => null],      // L
        ]));

        // === WOMEN'S JEANS (Category 9) ===

        // Product 18: High Waist Blue Jeans
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(18, [
            ['value_id' => 7, 'stock' => 12, 'price' => null],      // 28
            ['value_id' => 8, 'stock' => 20, 'price' => null],      // 30
            ['value_id' => 9, 'stock' => 18, 'price' => null],      // 32
            ['value_id' => 10, 'stock' => 10, 'price' => null],     // 34
        ]));

        // Product 19: Ripped Skinny Jeans
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(19, [
            ['value_id' => 7, 'stock' => 10, 'price' => null],      // 28
            ['value_id' => 8, 'stock' => 15, 'price' => null],      // 30
            ['value_id' => 9, 'stock' => 12, 'price' => null],      // 32
            ['value_id' => 10, 'stock' => 8, 'price' => null],      // 34
        ]));

        // === WOMEN'S HEELS (Category 10) ===

        // Product 20: Elegant Block Heels
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(20, [
            ['value_id' => 12, 'stock' => 6, 'price' => null],      // 6
            ['value_id' => 13, 'stock' => 10, 'price' => null],     // 7
            ['value_id' => 14, 'stock' => 8, 'price' => null],      // 8
        ]));

        // Product 21: Stiletto High Heels
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(21, [
            ['value_id' => 12, 'stock' => 5, 'price' => null],      // 6
            ['value_id' => 13, 'stock' => 8, 'price' => null],      // 7
            ['value_id' => 14, 'stock' => 6, 'price' => null],      // 8
        ]));

        // === KIDS PRODUCTS (Category 12, 13) - Age-based sizes ===

        // Product 22: Kids Superhero T-Shirt
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(22, [
            ['value_id' => 58, 'stock' => 20, 'price' => null],     // 2-3Y
            ['value_id' => 59, 'stock' => 25, 'price' => null],     // 3-4Y
            ['value_id' => 60, 'stock' => 30, 'price' => null],     // 4-5Y
            ['value_id' => 61, 'stock' => 25, 'price' => null],     // 5-6Y
            ['value_id' => 62, 'stock' => 20, 'price' => 449.00],   // 6-7Y
            ['value_id' => 63, 'stock' => 15, 'price' => 449.00],   // 7-8Y
        ]));

        // Product 23: Boys Casual Shorts
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(23, [
            ['value_id' => 58, 'stock' => 15, 'price' => null],     // 2-3Y
            ['value_id' => 59, 'stock' => 20, 'price' => null],     // 3-4Y
            ['value_id' => 60, 'stock' => 22, 'price' => null],     // 4-5Y
            ['value_id' => 61, 'stock' => 18, 'price' => null],     // 5-6Y
            ['value_id' => 62, 'stock' => 15, 'price' => null],     // 6-7Y
        ]));

        // Product 24: Girls Princess Dress
        $productSizeVariations = array_merge($productSizeVariations, $this->generateSizeVariations(24, [
            ['value_id' => 58, 'stock' => 10, 'price' => null],     // 2-3Y
            ['value_id' => 59, 'stock' => 15, 'price' => null],     // 3-4Y
            ['value_id' => 60, 'stock' => 18, 'price' => null],     // 4-5Y
            ['value_id' => 61, 'stock' => 15, 'price' => null],     // 5-6Y
            ['value_id' => 62, 'stock' => 10, 'price' => 1099.00],  // 6-7Y
        ]));

        // Insert all variations
        if (!empty($productSizeVariations)) {
            // Insert in chunks to avoid memory issues
            foreach (array_chunk($productSizeVariations, 100) as $chunk) {
                DB::table('product_attributes')->insert($chunk);
            }
        }

        $this->command->info('Product size variations seeded successfully!');
        $this->command->info('Total variations: ' . count($productSizeVariations));
    }

    /**
     * Generate size variation records for a product
     */
    private function generateSizeVariations(int $productId, array $sizes): array
    {
        $variations = [];
        $sizeAttributeId = 1;

        foreach ($sizes as $size) {
            $variations[] = [
                'product_id' => $productId,
                'attribute_id' => $sizeAttributeId,
                'attribute_value_id' => $size['value_id'],
                'stock' => $size['stock'],
                'price' => $size['price'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $variations;
    }
}
