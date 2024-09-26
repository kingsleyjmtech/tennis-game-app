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
            'player1_name' => fake()->firstName(),
            'player2_name' => fake()->firstName(),
            'player1_points' => 0,
            'player2_points' => 0,
            'current_player' => 'player1',
        ];
    }
}
