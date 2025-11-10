<?php
// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        $products = [
            [
                'name' => 'Kaos Polos Premium',
                'slug' => 'kaos-polos-premium',
                'description' => 'Kaos polos dengan bahan premium cotton combed 30s, nyaman dipakai sehari-hari.',
                'price' => 85000,
                'sale_price' => 75000,
                'stock' => 50,
                'sku' => 'KPS-001',
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'colors' => json_encode(['Putih', 'Hitam', 'Navy']),
                'is_featured' => true,
            ],
            [
                'name' => 'Kemeja Flanel Casual',
                'slug' => 'kemeja-flanel-casual',
                'description' => 'Kemeja flanel dengan bahan tebal dan hangat, cocok untuk cuaca dingin.',
                'price' => 150000,
                'sale_price' => null,
                'stock' => 30,
                'sku' => 'KFL-001',
                'sizes' => json_encode(['M', 'L', 'XL']),
                'colors' => json_encode(['Merah', 'Biru', 'Hijau']),
                'is_featured' => true,
            ],
            [
                'name' => 'Celana Jeans Slim Fit',
                'slug' => 'celana-jeans-slim-fit',
                'description' => 'Celana jeans dengan model slim fit, bahan denim berkualitas tinggi.',
                'price' => 250000,
                'sale_price' => 220000,
                'stock' => 25,
                'sku' => 'CJ-001',
                'sizes' => json_encode(['28', '30', '32', '34']),
                'colors' => json_encode(['Biru Tua', 'Hitam']),
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'category_id' => $categories->random()->id,
                'is_active' => true,
            ]));
        }
    }
}