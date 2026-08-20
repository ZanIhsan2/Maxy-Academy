<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $computer = Category::where('name', 'Perangkat Komputer')->value('id');
        $accessories = Category::where('name', 'Aksesori')->value('id');
        Product::upsert([
            [
                'sku' => 'SKU-1001',
                'category_id' => $computer,
                'name' => 'Wireless Keyboard',
                'price' => 325000,
                'stock' => 24,
                'description' => 'Keyboard nirkabel untuk kebutuhan kerja harian.',
            ],
            [
                'sku' => 'SKU-1002',
                'category_id' => $accessories,
                'name' => 'USB-C Hub',
                'price' => 275000,
                'stock' => 18,
                'description' => 'Hub USB-C dengan port HDMI dan USB 3.0.',
            ],
            [
                'sku' => 'SKU-1003',
                'category_id' => $accessories,
                'name' => 'Laptop Stand',
                'price' => 450000,
                'stock' => 12,
                'description' => 'Stand laptop aluminium dengan desain minimalis.',
            ],
        ], ['sku'], ['category_id', 'name', 'price', 'stock', 'description']);
    }
}
