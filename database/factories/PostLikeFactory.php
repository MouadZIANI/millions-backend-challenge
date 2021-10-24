<?php

namespace Database\Factories;

use App\Models\PostLike;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostLikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostLike::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
        ];
    }
}
