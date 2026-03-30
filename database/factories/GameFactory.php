<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class GameFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [            
            'zassession_id' => fake()->unique()->numberBetween(1, 100),
            'boardgame_id' => fake()->numberBetween(1, 200),
            'host_user_id' => fake()->numberBetween(1, 10),
            'max_players' => fake()->numberBetween(1, 10),
            'start_time' => fake()->time('H:i:s'),
            'status' => fake()->randomElement(['open', 'limited', 'playing', 'finished']),
            'necesary_know_how' => fake()->boolean(),
        ];
    }
}
