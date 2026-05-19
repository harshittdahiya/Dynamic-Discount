<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'role' => 'customer',
            'status' => 'active',
        ]);

        $productCatalog = [
            'Electronics' => [
                ['name' => 'Wireless Bluetooth Headphones', 'image' => 'https://loremflickr.com/640/480/headphones?lock=1001'],
                ['name' => 'Gaming Mechanical Keyboard', 'image' => 'https://loremflickr.com/640/480/keyboard?lock=1002'],
                ['name' => 'Smart Fitness Watch', 'image' => 'https://loremflickr.com/640/480/smartwatch?lock=1003'],
                ['name' => 'Portable Bluetooth Speaker', 'image' => 'https://loremflickr.com/640/480/speaker?lock=1004'],
                ['name' => 'Full HD Monitor', 'image' => 'https://loremflickr.com/640/480/monitor?lock=1005'],
            ],
            'Furniture' => [
                ['name' => 'Wooden Office Desk', 'image' => 'https://loremflickr.com/640/480/office,desk?lock=1101'],
                ['name' => 'Modern Sofa Chair', 'image' => 'https://loremflickr.com/640/480/sofa,chair?lock=1102'],
                ['name' => 'Queen Size Bed Frame', 'image' => 'https://loremflickr.com/640/480/bedroom,bed?lock=1103'],
                ['name' => 'Dining Table Set', 'image' => 'https://loremflickr.com/640/480/dining,table?lock=1104'],
                ['name' => 'Adjustable Study Chair', 'image' => 'https://loremflickr.com/640/480/study,chair?lock=1105'],
            ],
            'Clothing' => [
                ['name' => 'Oversized Cotton Hoodie', 'image' => 'https://loremflickr.com/640/480/hoodie?lock=1201'],
                ['name' => 'Slim Fit Denim Jacket', 'image' => 'https://loremflickr.com/640/480/denim,jacket?lock=1202'],
                ['name' => 'Casual Printed T-Shirt', 'image' => 'https://loremflickr.com/640/480/tshirt?lock=1203'],
                ['name' => 'Men\'s Cargo Joggers', 'image' => 'https://loremflickr.com/640/480/joggers?lock=1204'],
                ['name' => 'Women\'s Winter Sweater', 'image' => 'https://loremflickr.com/640/480/sweater?lock=1205'],
            ],
            'Home & Kitchen' => [
                ['name' => 'Non-Stick Cookware Set', 'image' => 'https://loremflickr.com/640/480/cookware?lock=1301'],
                ['name' => 'Stainless Steel Water Bottle', 'image' => 'https://loremflickr.com/640/480/water,bottle?lock=1302'],
                ['name' => 'Electric Air Fryer', 'image' => 'https://loremflickr.com/640/480/air,fryer?lock=1303'],
                ['name' => 'Kitchen Knife Set', 'image' => 'https://loremflickr.com/640/480/kitchen,knife?lock=1304'],
                ['name' => 'Smart LED Table Lamp', 'image' => 'https://loremflickr.com/640/480/table,lamp?lock=1305'],
            ],
            'Books' => [
                ['name' => 'Atomic Habits', 'image' => 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg'],
                ['name' => 'Rich Dad Poor Dad', 'image' => 'https://covers.openlibrary.org/b/isbn/9781612680194-L.jpg'],
                ['name' => 'The Psychology of Money', 'image' => 'https://covers.openlibrary.org/b/isbn/9780857197689-L.jpg'],
                ['name' => 'Ikigai', 'image' => 'https://covers.openlibrary.org/b/isbn/9780143130727-L.jpg'],
                ['name' => 'Deep Work', 'image' => 'https://covers.openlibrary.org/b/isbn/9781455586691-L.jpg'],
            ],
            'Fashion' => [
                ['name' => 'Leather Crossbody Bag', 'image' => 'https://loremflickr.com/640/480/crossbody,bag?lock=1401'],
                ['name' => 'Luxury Analog Watch', 'image' => 'https://loremflickr.com/640/480/analog,watch?lock=1402'],
                ['name' => 'Classic Black Sunglasses', 'image' => 'https://loremflickr.com/640/480/sunglasses?lock=1403'],
                ['name' => 'Minimal Silver Necklace', 'image' => 'https://loremflickr.com/640/480/silver,necklace?lock=1404'],
                ['name' => 'Premium Sneakers', 'image' => 'https://loremflickr.com/640/480/sneakers?lock=1405'],
            ],
            'Sports & Outdoors' => [
                ['name' => 'Professional Yoga Mat', 'image' => 'https://loremflickr.com/640/480/yoga,mat?lock=1501'],
                ['name' => 'Adjustable Dumbbell Set', 'image' => 'https://loremflickr.com/640/480/dumbbells?lock=1502'],
                ['name' => 'Mountain Hiking Backpack', 'image' => 'https://loremflickr.com/640/480/hiking,backpack?lock=1503'],
                ['name' => 'Football Training Kit', 'image' => 'https://loremflickr.com/640/480/football?lock=1504'],
                ['name' => 'Outdoor Camping Tent', 'image' => 'https://loremflickr.com/640/480/camping,tent?lock=1505'],
            ],
            'Health & Beauty' => [
                ['name' => 'Vitamin C Face Serum', 'image' => 'https://loremflickr.com/640/480/face,serum?lock=1601'],
                ['name' => 'Organic Hair Care Kit', 'image' => 'https://loremflickr.com/640/480/hair,care?lock=1602'],
                ['name' => 'Matte Finish Lipstick Set', 'image' => 'https://loremflickr.com/640/480/lipstick?lock=1603'],
                ['name' => 'Facial Roller Massager', 'image' => 'https://loremflickr.com/640/480/facial,roller?lock=1604'],
                ['name' => 'Aloe Vera Skin Gel', 'image' => 'https://loremflickr.com/640/480/aloe,vera?lock=1605'],
            ],
            'Toys & Games' => [
                ['name' => 'Remote Control Racing Car', 'image' => 'https://loremflickr.com/640/480/remote,car?lock=1701'],
                ['name' => 'Building Blocks Set', 'image' => 'https://loremflickr.com/640/480/building,blocks?lock=1702'],
                ['name' => 'Puzzle Board Game', 'image' => 'https://loremflickr.com/640/480/board,game?lock=1703'],
                ['name' => 'Kids Learning Tablet', 'image' => 'https://loremflickr.com/640/480/kids,tablet?lock=1704'],
                ['name' => 'Action Figure Collection', 'image' => 'https://loremflickr.com/640/480/action,figure?lock=1705'],
            ],
            'Automotive' => [
                ['name' => 'Car Vacuum Cleaner', 'image' => 'https://loremflickr.com/640/480/car,vacuum?lock=1801'],
                ['name' => 'LED Headlight Bulbs', 'image' => 'https://loremflickr.com/640/480/headlight?lock=1802'],
                ['name' => 'Tire Pressure Inflator', 'image' => 'https://loremflickr.com/640/480/tire,inflator?lock=1803'],
                ['name' => 'Car Phone Mount', 'image' => 'https://loremflickr.com/640/480/car,phone?lock=1804'],
                ['name' => 'Waterproof Bike Cover', 'image' => 'https://loremflickr.com/640/480/bike,cover?lock=1805'],
            ],
            'Grocery' => [
                ['name' => 'Organic Basmati Rice', 'image' => 'https://loremflickr.com/640/480/rice?lock=1901'],
                ['name' => 'Premium Dry Fruits Pack', 'image' => 'https://loremflickr.com/640/480/dry,fruits?lock=1902'],
                ['name' => 'Natural Peanut Butter', 'image' => 'https://loremflickr.com/640/480/peanut,butter?lock=1903'],
                ['name' => 'Instant Coffee Jar', 'image' => 'https://loremflickr.com/640/480/coffee,jar?lock=1904'],
                ['name' => 'Green Tea Collection', 'image' => 'https://loremflickr.com/640/480/green,tea?lock=1905'],
            ],
            'Office Supplies' => [
                ['name' => 'Wireless Office Mouse', 'image' => 'https://loremflickr.com/640/480/computer,mouse?lock=2001'],
                ['name' => 'Premium Notebook Set', 'image' => 'https://loremflickr.com/640/480/notebook?lock=2002'],
                ['name' => 'Ergonomic Laptop Stand', 'image' => 'https://loremflickr.com/640/480/laptop,stand?lock=2003'],
                ['name' => 'Professional Pen Pack', 'image' => 'https://loremflickr.com/640/480/pen?lock=2004'],
                ['name' => 'Desktop Organizer Kit', 'image' => 'https://loremflickr.com/640/480/desk,organizer?lock=2005'],
            ],
        ];

        foreach ($productCatalog as $categoryName => $products) {
            $slug = Str::slug($categoryName);

            $category = Category::updateOrCreate(
                ['category_slug' => $slug],
                ['category_name' => $categoryName, 'status' => 'active']
            );

            $existingProducts = Product::where('category_id', $category->getKey())
                ->orderBy('created_at', 'asc')
                ->get()
                ->values();

            foreach ($products as $index => $productData) {
                $productName = $productData['name'];
                $product = $existingProducts->get($index) ?? new Product();

                $product->fill([
                    'category_id' => $category->getKey(),
                    'product_name' => $productName,
                    'product_slug' => Str::slug($productName),
                    'product_image' => $productData['image'],
                    'price' => $product->price ?? rand(100, 10000) / 100,
                    'stock' => $product->stock ?? rand(10, 200),
                    'description' => 'This is a sample description for ' . $productName . '.',
                    'status' => 'active',
                ]);

                $product->save();
            }

            foreach ($existingProducts->slice(count($products)) as $extraProduct) {
                $extraProduct->delete();
            }
        }
    }
}
