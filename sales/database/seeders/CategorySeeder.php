<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::upsert([
            ['name' => 'Perangkat Komputer', 'description' => 'Perangkat pendukung komputer dan laptop.'],
            ['name' => 'Aksesori', 'description' => 'Aksesori untuk kebutuhan kerja dan produktivitas.'],
            ['name' => 'Peralatan Kantor', 'description' => 'Peralatan untuk kebutuhan operasional kantor.'],
        ], ['name'], ['description']);
    }
}
