<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Men's T-Shirts (Category 2)
            [
                'category_id' => 2,
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-tshirt',
                'description' => 'Comfortable cotton t-shirt perfect for everyday wear. Made from 100% premium cotton fabric.',
                'price' => 599.00,
                'sale_price' => 449.00,
                'stock' => 50,
                'is_active' => true,
                'is_featured' => true,
                'image' => 'products/tshirt-1.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'Graphic Print T-Shirt',
                'slug' => 'graphic-print-tshirt',
                'description' => 'Trendy graphic print t-shirt with modern design. Breathable and stylish.',
                'price' => 799.00,
                'sale_price' => null,
                'stock' => 35,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/tshirt-2.jpg'
            ],
            [
                'category_id' => 2,
                'name' => 'V-Neck T-Shirt',
                'slug' => 'v-neck-tshirt',
                'description' => 'Slim fit v-neck t-shirt available in multiple colors. Perfect for casual outings.',
                'price' => 649.00,
                'sale_price' => 549.00,
                'stock' => 45,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/tshirt-3.jpg'
            ],

            // Men's Shirts (Category 3)
            [
                'category_id' => 3,
                'name' => 'Formal White Shirt',
                'slug' => 'formal-white-shirt',
                'description' => 'Classic formal white shirt for office and business meetings. Wrinkle-free fabric.',
                'price' => 1299.00,
                'sale_price' => 999.00,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => true,
                'image' => 'products/shirt-1.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Casual Denim Shirt',
                'slug' => 'casual-denim-shirt',
                'description' => 'Trendy denim shirt for casual wear. Comfortable and durable material.',
                'price' => 1499.00,
                'sale_price' => null,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/shirt-2.jpg'
            ],
            [
                'category_id' => 3,
                'name' => 'Checkered Casual Shirt',
                'slug' => 'checkered-casual-shirt',
                'description' => 'Stylish checkered pattern shirt perfect for weekend outings.',
                'price' => 1199.00,
                'sale_price' => 899.00,
                'stock' => 40,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/shirt-3.jpg'
            ],

            // Men's Jeans (Category 4)
            [
                'category_id' => 4,
                'name' => 'Slim Fit Blue Jeans',
                'slug' => 'slim-fit-blue-jeans',
                'description' => 'Classic slim fit blue jeans with comfortable stretch fabric. Perfect fit guaranteed.',
                'price' => 2199.00,
                'sale_price' => 1799.00,
                'stock' => 40,
                'is_active' => true,
                'is_featured' => true,
                'image' => 'products/jeans-1.jpg'
            ],
            [
                'category_id' => 4,
                'name' => 'Black Skinny Jeans',
                'slug' => 'black-skinny-jeans',
                'description' => 'Modern black skinny jeans with premium denim quality.',
                'price' => 2499.00,
                'sale_price' => null,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/jeans-2.jpg'
            ],

            // Men's Shoes (Category 5)
            [
                'category_id' => 5,
                'name' => 'Leather Formal Shoes',
                'slug' => 'leather-formal-shoes',
                'description' => 'Premium leather formal shoes for office and special occasions. Comfortable sole.',
                'price' => 3499.00,
                'sale_price' => 2999.00,
                'stock' => 20,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/shoes-1.jpg'
            ],
            [
                'category_id' => 5,
                'name' => 'Sports Running Shoes',
                'slug' => 'sports-running-shoes',
                'description' => 'Lightweight running shoes with excellent cushioning and support.',
                'price' => 2999.00,
                'sale_price' => 2499.00,
                'stock' => 35,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/shoes-2.jpg'
            ],

            // Women's Dresses (Category 7)
            [
                'category_id' => 7,
                'name' => 'Floral Summer Dress',
                'slug' => 'floral-summer-dress',
                'description' => 'Beautiful floral summer dress perfect for parties and events. Lightweight fabric.',
                'price' => 1999.00,
                'sale_price' => 1599.00,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => true,
                'image' => 'products/dress-1.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Elegant Evening Dress',
                'slug' => 'elegant-evening-dress',
                'description' => 'Sophisticated evening dress for formal occasions. Premium quality fabric.',
                'price' => 3999.00,
                'sale_price' => null,
                'stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/dress-2.jpg'
            ],
            [
                'category_id' => 7,
                'name' => 'Casual Midi Dress',
                'slug' => 'casual-midi-dress',
                'description' => 'Comfortable midi dress for everyday wear. Available in multiple colors.',
                'price' => 1499.00,
                'sale_price' => 1199.00,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/dress-3.jpg'
            ],

            // Women's Tops (Category 8)
            [
                'category_id' => 8,
                'name' => 'Sleeveless Casual Top',
                'slug' => 'sleeveless-casual-top',
                'description' => 'Trendy sleeveless top perfect for summer. Breathable cotton blend.',
                'price' => 799.00,
                'sale_price' => 599.00,
                'stock' => 40,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/top-1.jpg'
            ],
            [
                'category_id' => 8,
                'name' => 'Printed Crop Top',
                'slug' => 'printed-crop-top',
                'description' => 'Stylish printed crop top with modern design. Perfect for casual outings.',
                'price' => 699.00,
                'sale_price' => null,
                'stock' => 45,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/top-2.jpg'
            ],

            // Women's Jeans (Category 9)
            [
                'category_id' => 9,
                'name' => 'High Waist Blue Jeans',
                'slug' => 'high-waist-blue-jeans',
                'description' => 'Trendy high waist blue jeans with perfect fit. Stretchable denim.',
                'price' => 2299.00,
                'sale_price' => 1899.00,
                'stock' => 35,
                'is_active' => true,
                'is_featured' => true,
                'image' => 'products/womens-jeans-1.jpg'
            ],
            [
                'category_id' => 9,
                'name' => 'Ripped Skinny Jeans',
                'slug' => 'ripped-skinny-jeans',
                'description' => 'Fashionable ripped skinny jeans for a modern look.',
                'price' => 2499.00,
                'sale_price' => null,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/womens-jeans-2.jpg'
            ],

            // Women's Heels (Category 10)
            [
                'category_id' => 10,
                'name' => 'Elegant Block Heels',
                'slug' => 'elegant-block-heels',
                'description' => 'Comfortable block heels perfect for office and parties. Cushioned insole.',
                'price' => 2999.00,
                'sale_price' => 2499.00,
                'stock' => 20,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/heels-1.jpg'
            ],
            [
                'category_id' => 10,
                'name' => 'Stiletto High Heels',
                'slug' => 'stiletto-high-heels',
                'description' => 'Classic stiletto heels for formal occasions. Premium quality.',
                'price' => 3499.00,
                'sale_price' => null,
                'stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/heels-2.jpg'
            ],

            // Kids Boys Clothing (Category 12)
            [
                'category_id' => 12,
                'name' => 'Kids Superhero T-Shirt',
                'slug' => 'kids-superhero-tshirt',
                'description' => 'Fun superhero print t-shirt for boys. Soft and comfortable fabric.',
                'price' => 499.00,
                'sale_price' => 399.00,
                'stock' => 50,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/kids-tshirt-1.jpg'
            ],
            [
                'category_id' => 12,
                'name' => 'Boys Casual Shorts',
                'slug' => 'boys-casual-shorts',
                'description' => 'Comfortable casual shorts for active boys. Durable material.',
                'price' => 599.00,
                'sale_price' => null,
                'stock' => 40,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/kids-shorts-1.jpg'
            ],

            // Kids Girls Clothing (Category 13)
            [
                'category_id' => 13,
                'name' => 'Girls Princess Dress',
                'slug' => 'girls-princess-dress',
                'description' => 'Adorable princess dress for little girls. Perfect for parties.',
                'price' => 1299.00,
                'sale_price' => 999.00,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/girls-dress-1.jpg'
            ],

            // Electronics - Smartphones (Category 15)
            [
                'category_id' => 15,
                'name' => 'Smartphone Pro Max',
                'slug' => 'smartphone-pro-max',
                'description' => 'Latest flagship smartphone with advanced camera and 5G connectivity.',
                'price' => 79999.00,
                'sale_price' => 74999.00,
                'stock' => 15,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/phone-1.jpg'
            ],

            // Electronics - Headphones (Category 16)
            [
                'category_id' => 16,
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'Premium wireless headphones with noise cancellation and long battery life.',
                'price' => 4999.00,
                'sale_price' => 3999.00,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/headphones-1.jpg'
            ],

            // Accessories - Bags (Category 18)
            [
                'category_id' => 18,
                'name' => 'Leather Laptop Bag',
                'slug' => 'leather-laptop-bag',
                'description' => 'Professional leather laptop bag with multiple compartments. Water resistant.',
                'price' => 2999.00,
                'sale_price' => 2499.00,
                'stock' => 20,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/bag-1.jpg'
            ],

            // Accessories - Watches (Category 19)
            [
                'category_id' => 19,
                'name' => 'Smart Watch Pro',
                'slug' => 'smart-watch-pro',
                'description' => 'Advanced smart watch with fitness tracking and notification features.',
                'price' => 9999.00,
                'sale_price' => 8499.00,
                'stock' => 18,
                'is_active' => true,
                'is_featured' => false,
                'image' => 'products/watch-1.jpg'
            ],
        ];

        $productAttributes = [];

        foreach ($products as $index => $product) {
            $image = $product['image'];
            unset($product['image']);

            $product['created_at'] = now();
            $product['updated_at'] = now();

            $productId = \Illuminate\Support\Facades\DB::table('products')->insertGetId($product);

            // Add product image
            \Illuminate\Support\Facades\DB::table('product_images')->insert([
                'product_id' => $productId,
                'image_path' => $image,
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Define attributes for each product type
            switch ($index) {
                // Men's T-Shirts
                case 0: // Classic Cotton T-Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 3]; // Size: M
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 36]; // Brand: StyleHub
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 47]; // Fit: Regular Fit
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 51]; // Pattern: Solid
                    break;
                case 1: // Graphic Print T-Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 4]; // Size: L
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 38]; // Brand: TrendWear
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 57]; // Pattern: Graphic
                    break;
                case 2: // V-Neck T-Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 3]; // Size: M
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 17]; // Color: White
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 46]; // Fit: Slim Fit
                    break;

                // Men's Shirts
                case 3: // Formal White Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 4]; // Size: L
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 17]; // Color: White
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 40]; // Brand: ClassicWear
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 46]; // Fit: Slim Fit
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 56]; // Pattern: Plain
                    break;
                case 4: // Casual Denim Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 4]; // Size: L
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 28]; // Material: Denim
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 39]; // Brand: UrbanStyle
                    break;
                case 5: // Checkered Casual Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 3]; // Size: M
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 54]; // Pattern: Checkered
                    break;

                // Men's Jeans
                case 6: // Slim Fit Blue Jeans
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 9]; // Size: 32
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 28]; // Material: Denim
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 38]; // Brand: TrendWear
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 46]; // Fit: Slim Fit
                    break;
                case 7: // Black Skinny Jeans
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 8]; // Size: 30
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 28]; // Material: Denim
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 49]; // Fit: Skinny Fit
                    break;

                // Men's Shoes
                case 8: // Leather Formal Shoes
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 14]; // Size: 8
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 29]; // Material: Leather
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 40]; // Brand: ClassicWear
                    break;
                case 9: // Sports Running Shoes
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 15]; // Size: 9
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 17]; // Color: White
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 35]; // Material: Mesh
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 41]; // Brand: SportMax
                    break;

                // Women's Dresses
                case 10: // Floral Summer Dress
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 3]; // Size: M
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 25]; // Color: Multicolor
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 31]; // Material: Chiffon
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 37]; // Brand: FashionPro
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 55]; // Pattern: Floral
                    break;
                case 11: // Elegant Evening Dress
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 3]; // Size: M
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 30]; // Material: Silk
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 44]; // Brand: LuxeStyle
                    break;
                case 12: // Casual Midi Dress
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 45]; // Brand: ComfortZone
                    break;

                // Women's Tops
                case 13: // Sleeveless Casual Top
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 17]; // Color: White
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 51]; // Pattern: Solid
                    break;
                case 14: // Printed Crop Top
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 23]; // Color: Pink
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 27]; // Material: Polyester
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 52]; // Pattern: Printed
                    break;

                // Women's Jeans
                case 15: // High Waist Blue Jeans
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 8]; // Size: 30
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 28]; // Material: Denim
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 38]; // Brand: TrendWear
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 46]; // Fit: Slim Fit
                    break;
                case 16: // Ripped Skinny Jeans
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 7]; // Size: 28
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 28]; // Material: Denim
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 5, 'attribute_value_id' => 49]; // Fit: Skinny Fit
                    break;

                // Women's Heels
                case 17: // Elegant Block Heels
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 13]; // Size: 7
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 32]; // Material: Synthetic
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 44]; // Brand: LuxeStyle
                    break;
                case 18: // Stiletto High Heels
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 12]; // Size: 6
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 19]; // Color: Red
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 29]; // Material: Leather
                    break;

                // Kids
                case 19: // Kids Superhero T-Shirt
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 18]; // Color: Blue
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 42]; // Brand: KidsJoy
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 6, 'attribute_value_id' => 52]; // Pattern: Printed
                    break;
                case 20: // Boys Casual Shorts
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 26]; // Material: Cotton
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 42]; // Brand: KidsJoy
                    break;
                case 21: // Girls Princess Dress
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 1, 'attribute_value_id' => 2]; // Size: S
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 23]; // Color: Pink
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 30]; // Material: Silk
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 42]; // Brand: KidsJoy
                    break;

                // Electronics
                case 22: // Smartphone Pro Max
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 43]; // Brand: TechGear
                    break;
                case 23: // Wireless Bluetooth Headphones
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 43]; // Brand: TechGear
                    break;

                // Accessories
                case 24: // Leather Laptop Bag
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 3, 'attribute_value_id' => 29]; // Material: Leather
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 39]; // Brand: UrbanStyle
                    break;
                case 25: // Smart Watch Pro
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 2, 'attribute_value_id' => 16]; // Color: Black
                    $productAttributes[] = ['product_id' => $productId, 'attribute_id' => 4, 'attribute_value_id' => 43]; // Brand: TechGear
                    break;
            }
        }

        // Insert all product attributes
        if (!empty($productAttributes)) {
            foreach ($productAttributes as &$attr) {
                $attr['created_at'] = now();
                $attr['updated_at'] = now();
            }
            \Illuminate\Support\Facades\DB::table('product_attributes')->insert($productAttributes);
        }
    }
}
