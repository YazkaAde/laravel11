<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Category::create([
        //     'name' => 'Web Design',
        //     'slug' => 'web-design'
        // ]);

        // Post::create([
        //     'title' => 'Artikel Web Design',
        //     'author_id' => 1,
        //     'category_id' => 1,
        //     'slug' => 'web_deign_article-1',
        //     'body' => 'This version of LEMON MILK is absolutely free for personal, educational, non-profit, or charitable use. 
        // For commercial use, kindly donate me (pay as you want) as an appreciation. If you want to donate, my PayPal address is marsnev@marsnev.com
        // Every donation is greatly appreciated. '
        // ]);

        $this->call([CategorySeeder::class, UserSeeder::class]);
        Post::factory(50)->recycle([
            Category::all(),
            User::all()
        ])->create();

    }
}
