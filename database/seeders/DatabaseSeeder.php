<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)
            ->has(Post::factory(3), 'posts')
            ->create();

        Post::all()->each(function ($post) {
            for ($i = 0; $i < random_int(2, 6); $i++) {
                PostLike::factory()
                    ->for($post, 'post')
                    ->for(User::query()->inRandomOrder()->first(), 'reacter')
                    ->create();
            }
        });
    }
}
