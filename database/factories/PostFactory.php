<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6, true),
            'description' => $this->faker->boolean(90) ? $this->faker->paragraph(3, true) : null,
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']);
            },
            'user_id' => function () {
                // Make sure we have at least one user
                $user = User::inRandomOrder()->first();
                if (!$user) {
                    return User::factory()->create()->id;
                }
                return $user->id;
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
