<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Pria', 'slug' => 'pria'],
            ['name' => 'Wanita', 'slug' => 'wanita'],
            ['name' => 'Anak-anak', 'slug' => 'anak-anak'],
            ['name' => 'Kaos', 'slug' => 'kaos'],
            ['name' => 'Kemeja', 'slug' => 'kemeja'],
            ['name' => 'Celana', 'slug' => 'celana'],
            ['name' => 'Jaket', 'slug' => 'jaket'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}