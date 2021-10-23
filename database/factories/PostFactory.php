<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $images = [
            '/posts/post-1.jpg',
            '/posts/post-2.jpg',
            '/posts/post-3.jpg',
            '/posts/post-4.jpg',
        ];

        return [
            'uuid' => Str::uuid(),
            'description' => $this->faker->paragraph(5),
            'image' => Arr::random($images),
        ];
    }
}
