<?php

namespace App\Livewire;

use App\Services\TennisGameService;
use Exception;
use Livewire\Component;
use App\Models\TennisGame as TennisGameModel;

class TennisGame extends Component
{
    public TennisGameModel $tennisGame;
    public string $score;
    public string $currentPlayer;

    protected $listeners = ['playerOneScores', 'playerTwoScores'];

    public function mount(): void
    {
        $this->createNewGame();
    }

    /**
     * Create a new game and reset the state.
     */
    public function createNewGame(): void
    {
        $this->tennisGame = TennisGameModel::query()->create([
            'player1_name' => 'Player 1',
            'player2_name' => 'Player 2',
            'player1_points' => 0,
            'player2_points' => 0,
        ]);

        $this->updateGameState();
    }

    /**
     * Reset the current game to its initial state.
     */
    public function resetGame(): void
    {
        $this->tennisGame->update([
            'player1_points' => 0,
            'player2_points' => 0,
            'winner' => null,
            'current_player' => 'player1',
        ]);

        $this->updateGameState();
    }

    /**
     * @throws Exception
     */
    public function playerOneScores(): void
    {
        $service = new TennisGameService($this->tennisGame);
        $service->playerOnePlay();
        $this->updateGameState();
    }

    /**
     * @throws Exception
     */
    public function playerTwoScores(): void
    {
        $service = new TennisGameService($this->tennisGame);
        $service->playerTwoPlay();
        $this->updateGameState();
    }

    public function updateGameState(): void
    {
        $service = new TennisGameService($this->tennisGame);
        $this->score = $service->getScore();
        $this->currentPlayer = $service->getCurrentPlayer();
    }

    public function render()
    {
        return view('livewire.tennis-game');
    }
}
