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
    public function getScore(): string
    {
        if ($this->tennisGame->player1_points == $this->tennisGame->player2_points) {
            return $this->equalScore();
        }

        if ($this->tennisGame->player1_points >= 4 || $this->tennisGame->player2_points >= 4) {
            return $this->advantageOrWin();
        }

        return $this->pointName($this->tennisGame->player1_points) . ' - ' . $this->pointName($this->tennisGame->player2_points);
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
            return $this->tennisGame->player1_name . " Wins";
        }
        $this->tennisGame->update(['winner' => $this->tennisGame->player2_name]);
        return $this->tennisGame->player2_name . " Wins";
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
     * Update the score for the given player.
     * @throws Exception
     */
    public function updateScore(string $player, int $increment): void
    {
        if ($this->tennisGame->winner) {
            throw new Exception('Game is already over.');
        }

        if (!in_array($increment, [0, 1])) {
            throw new InvalidArgumentException('Increment must be either 0 or 1.');
        }

        if ($increment === 1) {
            if ($player === 'player1') {
                $this->tennisGame->player1_points += 1;
                $this->tennisGame->current_player = 'player2'; // Switch to Player 2's turn
            } elseif ($player === 'player2') {
                $this->tennisGame->player2_points += 1;
                $this->tennisGame->current_player = 'player1'; // Switch to Player 1's turn
            }
        }

        $this->tennisGame->save();
    }

    /**
     * Get the current player who should play next.
     */
    public function getCurrentPlayer(): string
    {
        return $this->tennisGame->current_player;
    }
}
