<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Politik', 'slug' => 'politik', 'description' => 'Berita politik terkini'],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi', 'description' => 'Berita ekonomi dan bisnis'],
            ['name' => 'Sosial', 'slug' => 'sosial', 'description' => 'Berita sosial dan budaya'],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'description' => 'Berita olahraga terbaru'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'description' => 'Berita teknologi dan digital'],
            ['name' => 'Hiburan', 'slug' => 'hiburan', 'description' => 'Berita hiburan dan selebriti']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
