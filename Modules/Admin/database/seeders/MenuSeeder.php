<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate(); // Clear existing menus

        // Main Menu
        $homeMenu = Menu::create(['name' => 'Home', 'url' => '/', 'type' => 'main', 'sort_order' => 1]);

        // Men's Menu
        $menMenu = Menu::create(['name' => 'Men', 'url' => '/category/men', 'type' => 'main', 'sort_order' => 2]);
        Menu::create(['name' => 'T-Shirts', 'url' => '/category/mens-tshirts', 'type' => 'main', 'parent_id' => $menMenu->id, 'sort_order' => 1]);
        Menu::create(['name' => 'Shirts', 'url' => '/category/mens-shirts', 'type' => 'main', 'parent_id' => $menMenu->id, 'sort_order' => 2]);
        Menu::create(['name' => 'Jeans', 'url' => '/category/mens-jeans', 'type' => 'main', 'parent_id' => $menMenu->id, 'sort_order' => 3]);
        Menu::create(['name' => 'Shoes', 'url' => '/category/mens-shoes', 'type' => 'main', 'parent_id' => $menMenu->id, 'sort_order' => 4]);

        // Women's Menu
        $womenMenu = Menu::create(['name' => 'Women', 'url' => '/category/women', 'type' => 'main', 'sort_order' => 3]);
        Menu::create(['name' => 'Dresses', 'url' => '/category/womens-dresses', 'type' => 'main', 'parent_id' => $womenMenu->id, 'sort_order' => 1]);
        Menu::create(['name' => 'Tops', 'url' => '/category/womens-tops', 'type' => 'main', 'parent_id' => $womenMenu->id, 'sort_order' => 2]);
        Menu::create(['name' => 'Jeans', 'url' => '/category/womens-jeans', 'type' => 'main', 'parent_id' => $womenMenu->id, 'sort_order' => 3]);
        Menu::create(['name' => 'Heels', 'url' => '/category/womens-heels', 'type' => 'main', 'parent_id' => $womenMenu->id, 'sort_order' => 4]);

        // Kids Menu
        $kidsMenu = Menu::create(['name' => 'Kids', 'url' => '/category/kids', 'type' => 'main', 'sort_order' => 4]);
        Menu::create(['name' => 'Boys Clothing', 'url' => '/category/boys-clothing', 'type' => 'main', 'parent_id' => $kidsMenu->id, 'sort_order' => 1]);
        Menu::create(['name' => 'Girls Clothing', 'url' => '/category/girls-clothing', 'type' => 'main', 'parent_id' => $kidsMenu->id, 'sort_order' => 2]);

        // Electronics Menu
        $electronicsMenu = Menu::create(['name' => 'Electronics', 'url' => '/category/electronics', 'type' => 'main', 'sort_order' => 5]);
        Menu::create(['name' => 'Smartphones', 'url' => '/category/smartphones', 'type' => 'main', 'parent_id' => $electronicsMenu->id, 'sort_order' => 1]);
        Menu::create(['name' => 'Headphones', 'url' => '/category/headphones', 'type' => 'main', 'parent_id' => $electronicsMenu->id, 'sort_order' => 2]);

        // Accessories Menu
        $accessoriesMenu = Menu::create(['name' => 'Accessories', 'url' => '/category/accessories', 'type' => 'main', 'sort_order' => 6]);
        Menu::create(['name' => 'Bags', 'url' => '/category/bags', 'type' => 'main', 'parent_id' => $accessoriesMenu->id, 'sort_order' => 1]);
        Menu::create(['name' => 'Watches', 'url' => '/category/watches', 'type' => 'main', 'parent_id' => $accessoriesMenu->id, 'sort_order' => 2]);

        // Footer Menu Sections
        // 1. Categories
        $catSection = Menu::create(['name' => 'Categories', 'url' => '#', 'type' => 'footer', 'sort_order' => 1]);
        $catLinks = [
            ['name' => 'Men Fashion', 'url' => '/category/men'],
            ['name' => 'Women Fashion', 'url' => '/category/women'],
            ['name' => 'Kids Wear', 'url' => '/category/kids'],
            ['name' => 'Electronics', 'url' => '/category/electronics'],
            ['name' => 'Accessories', 'url' => '/category/accessories'],
        ];
        foreach ($catLinks as $key => $link) {
            $link['type'] = 'footer';
            $link['parent_id'] = $catSection->id;
            $link['sort_order'] = $key + 1;
            Menu::create($link);
        }

        // 2. Useful Links
        $linkSection = Menu::create(['name' => 'Useful Links', 'url' => '#', 'type' => 'footer', 'sort_order' => 2]);
        $usefulLinks = [
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Collections', 'url' => '#'],
            ['name' => 'About Us', 'url' => '#'],
            ['name' => 'Blogs', 'url' => '#'],
            ['name' => 'Offers', 'url' => '#'],
            ['name' => 'Search', 'url' => '#'],
        ];
        foreach ($usefulLinks as $key => $link) {
            $link['type'] = 'footer';
            $link['parent_id'] = $linkSection->id;
            $link['sort_order'] = $key + 1;
            Menu::create($link);
        }

        // 3. Help Center
        $helpSection = Menu::create(['name' => 'Help Center', 'url' => '#', 'type' => 'footer', 'sort_order' => 3]);
        $helpLinks = [
            ['name' => 'My Account', 'url' => '#'],
            ['name' => 'My Orders', 'url' => '#'],
            ['name' => 'Track Order', 'url' => '#'],
            ['name' => 'Wishlist', 'url' => '#'],
            ['name' => 'FAQ\'s', 'url' => '#'],
            ['name' => 'Contact Us', 'url' => '#'],
        ];
        foreach ($helpLinks as $key => $link) {
            $link['type'] = 'footer';
            $link['parent_id'] = $helpSection->id;
            $link['sort_order'] = $key + 1;
            Menu::create($link);
        }
    }
}
