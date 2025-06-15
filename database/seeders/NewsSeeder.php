<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        $news = [
            [
                'title' => 'Pemerintah Umumkan Kebijakan Baru',
                'slug' => Str::slug('Pemerintah Umumkan Kebijakan Baru'),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
                'excerpt' => 'Pemerintah mengumumkan kebijakan baru terkait...',
                'status' => 'draft',
                'author_id' => $users->where('role', 'wartawan')->first()->id,
                'category_id' => $categories->where('slug', 'politik')->first()->id,
                'thumbnail' => 'news1.jpg'
            ],
            [
                'title' => 'Ekonomi Indonesia Tumbuh 5%',
                'slug' => Str::slug('Ekonomi Indonesia Tumbuh 5%'),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
                'excerpt' => 'Ekonomi Indonesia tumbuh 5% pada kuartal terakhir...',
                'status' => 'published',
                'author_id' => $users->where('role', 'wartawan')->first()->id,
                'category_id' => $categories->where('slug', 'ekonomi')->first()->id,
                'thumbnail' => 'news2.jpg'
            ],
            [
                'title' => 'Piala Dunia Dimulai',
                'slug' => Str::slug('Piala Dunia Dimulai'),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
                'excerpt' => 'Piala Dunia sepak bola dimulai hari ini...',
                'status' => 'draft',
                'author_id' => $users->where('role', 'wartawan')->first()->id,
                'category_id' => $categories->where('slug', 'olahraga')->first()->id,
                'thumbnail' => 'news3.jpg'
            ]
        ];

        foreach ($news as $newsData) {
            News::create($newsData);
        }
    }
}
