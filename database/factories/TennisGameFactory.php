<?php

namespace Database\Factories;

use App\Models\TennisGame;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TennisGameFactory extends Factory
{
    protected $model = TennisGame::class;

    public function definition(): array
    {
        return [
            'player1_name' => $this->faker->name,
            'player2_name' => $this->faker->name,
            'player1_points' => 0,
            'player2_points' => 0,
            'current_player' => 'player1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
