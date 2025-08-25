<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Government>
 */
class GovernmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'available_population' => config('game.settings.starting_population', 50),
            'money' => config('game.economy.starting_money', 5000),
            'economy' => 1,
            'health' => 1,
            'safety' => 1,
            'education' => 1,
            'overall' => 4,
            'user_id' => User::noGovernment()->inRandomOrder()->first()->id
        ];
    }
}
