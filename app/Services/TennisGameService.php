<?php

namespace App\Services;

use App\Models\TennisGame;
use Exception;
use InvalidArgumentException;

class TennisGameService
{
    protected TennisGame $tennisGame;

    public function __construct(TennisGame $tennisGame)
    {
        $this->tennisGame = $tennisGame;
    }

    /**
     * Get the current score in tennis terms.
     */
    public function scoreboard(): string
    {
        if ($this->tennisGame->winner) {
            return 'Won by ' . $this->tennisGame->winner;
        }

        if ($this->tennisGame->player1_points == $this->tennisGame->player2_points) {
            return $this->equalScore();
        }

        if ($this->tennisGame->player1_points >= 4 || $this->tennisGame->player2_points >= 4) {
            return $this->advantageOrWin();
        }

        return $this->pointName($this->tennisGame->player1_points) . '-' . $this->pointName($this->tennisGame->player2_points);
    }

    /**
     * Handle equal points (Deuce or regular tie).
     */
    private function equalScore(): string
    {
        if ($this->tennisGame->player1_points < 3) {
            return $this->pointName($this->tennisGame->player1_points) . " All";
        }
        return "Deuce";
    }

    /**
     * Handle advantage or win scenarios.
     */
    private function advantageOrWin(): string
    {
        $score_diff = $this->tennisGame->player1_points - $this->tennisGame->player2_points;
        if ($score_diff == 1) {
            return "Advantage " . $this->tennisGame->player1_name;
        } elseif ($score_diff == -1) {
            return "Advantage " . $this->tennisGame->player2_name;
        } elseif ($score_diff >= 2) {
            $this->tennisGame->update(['winner' => $this->tennisGame->player1_name]);
            return "Won by " . $this->tennisGame->player1_name;
        }
        $this->tennisGame->update(['winner' => $this->tennisGame->player2_name]);
        return "Won by " . $this->tennisGame->player2_name;
    }

    /**
     * Convert numeric points to tennis terms.
     */
    private function pointName(int $points): string
    {
        $names = ["Love", "Fifteen", "Thirty", "Forty"];
        return $names[$points] ?? "Invalid";
    }

    /**
     * Check if the game is complete.
     */
    public function isComplete(): bool
    {
        return (bool)$this->tennisGame->winner;
    }

    /**
     * Player 1 scores a point.
     * @throws Exception
     */
    public function player1Point(): void
    {
        if ($this->isComplete()) {
            throw new Exception('Game is already over.');
        }

        $this->updateScore('player1', 1);
    }

    /**
     * Player 2 scores a point.
     * @throws Exception
     */
    public function player2Point(): void
    {
        if ($this->isComplete()) {
            throw new Exception('Game is already over.');
        }

        $this->updateScore('player2', 1);
    }

    /**
     * Update the score for the given player and switch the current player.
     * @throws Exception
     */
    public function updateScore(string $player, int $increment): void
    {
        if ($this->tennisGame->winner) {
            throw new Exception('Game is already over.');
        }

        // Validate that the increment is either 0 or 1
        if (!in_array($increment, [0, 1], true)) {
            throw new InvalidArgumentException('Increment must be either 0 or 1.');
        }

        // Update the points for the current player
        if ($increment === 1) {
            if ($player === 'player1') {
                $this->tennisGame->player1_points += 1;
            } elseif ($player === 'player2') {
                $this->tennisGame->player2_points += 1;
            }
        }

        // Switch the current player
        $this->tennisGame->current_player = $player === 'player1' ? 'player2' : 'player1';

        $this->tennisGame->save();
    }

    /**
     * Function to handle the play for player 1, if it's their turn.
     * @throws Exception
     */
    public function playerOnePlay(): void
    {
        if ($this->tennisGame->current_player !== 'player1') {
            throw new Exception('It is not Player 1’s turn.');
        }

        // Player 1 scores a random point (either 0 or 1)
        $increment = rand(0, 1);

        if ($increment === 1) {
            $this->player1Point();
        } else {
            $this->updateScore('player1', $increment); // Still switch the player even if no point is added
        }
    }

    /**
     * Function to handle the play for player 2, if it's their turn.
     * @throws Exception
     */
    public function playerTwoPlay(): void
    {
        if ($this->tennisGame->current_player !== 'player2') {
            throw new Exception('It is not Player 2’s turn.');
        }

        // Player 2 scores a random point (either 0 or 1)
        $increment = rand(0, 1);

        if ($increment === 1) {
            $this->player2Point();
        } else {
            $this->updateScore('player2', $increment); // Still switch the player even if no point is added
        }
    }

    /**
     * Get the current player who should play next.
     */
    public function getCurrentPlayer(): string
    {
        return $this->tennisGame->current_player;
    }
}
