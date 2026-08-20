<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::upsert([
            [
                'sku' => 'SKU-1001',
                'name' => 'Wireless Keyboard',
                'price' => 325000,
                'stock' => 24,
                'description' => 'Keyboard nirkabel untuk kebutuhan kerja harian.',
            ],
            [
                'sku' => 'SKU-1002',
                'name' => 'USB-C Hub',
                'price' => 275000,
                'stock' => 18,
                'description' => 'Hub USB-C dengan port HDMI dan USB 3.0.',
            ],
            [
                'sku' => 'SKU-1003',
                'name' => 'Laptop Stand',
                'price' => 450000,
                'stock' => 12,
                'description' => 'Stand laptop aluminium dengan desain minimalis.',
            ],
        ], ['sku'], ['name', 'price', 'stock', 'description']);
    }
}
