<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TennisGame extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'player1_name',
        'player2_name',
        'player1_points',
        'player2_points',
        'winner',
        'current_player',
    ];

    /**
     * Initialize default attributes for a new game.
     */
    protected $attributes = [
        'player1_points' => 0,
        'player2_points' => 0,
        'current_player' => 'player1',  // Default start for Player 1
        'winner' => null,
    ];

    /**
     * Check if the game has ended by checking if there is a winner.
     */
    public function hasEnded(): bool
    {
        return $this->winner !== null;
    }

    /**
     * Check if the game is ongoing (i.e., no winner yet).
     */
    public function isOngoing(): bool
    {
        return $this->winner === null;
    }
}
