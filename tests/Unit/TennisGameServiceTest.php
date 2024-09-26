<?php

use App\Models\TennisGame;
use App\Services\TennisGameService;

beforeEach(function () {
    $this->tennisGame = TennisGame::factory()->make([
        'player1_name' => 'Player 1',
        'player2_name' => 'Player 2',
        'player1_points' => 0,
        'player2_points' => 0,
        'current_player' => 'player1',
    ]);

    $this->service = new TennisGameService($this->tennisGame);
});

it('initializes the game with Love All', function () {
    $score = $this->service->getScore();
    expect($score)->toBe('Love All');
});

it('correctly identifies the current player as player 1', function () {
    $currentPlayer = $this->service->getCurrentPlayer();
    expect($currentPlayer)->toBe('player1');
});

it('updates player 1 score correctly when incremented', function () {
    $this->service->updateScore('player1', 1);
    $this->tennisGame->refresh();

    expect($this->tennisGame->player1_points)->toBe(1)
        ->and($this->tennisGame->current_player)->toBe('player2')
        ->and($this->service->getScore())->toBe('Fifteen - Love');
});

it('updates player 2 score correctly when incremented', function () {
    $this->service->updateScore('player2', 1);
    $this->tennisGame->refresh();

    expect($this->tennisGame->player2_points)->toBe(1)
        ->and($this->tennisGame->current_player)->toBe('player1')
        ->and($this->service->getScore())->toBe('Love - Fifteen');
});

it('correctly handles deuce situation', function () {
    $this->tennisGame->player1_points = 3;
    $this->tennisGame->player2_points = 3;

    $score = $this->service->getScore();

    expect($score)->toBe('Deuce');
});

it('handles advantage for player 1', function () {
    $this->tennisGame->player1_points = 4;
    $this->tennisGame->player2_points = 3;

    $score = $this->service->getScore();

    expect($score)->toBe('Advantage Player 1');
});

it('handles win for player 1', function () {
    $this->tennisGame->player1_points = 5;
    $this->tennisGame->player2_points = 3;

    $score = $this->service->getScore();

    expect($score)->toBe('Player 1 Wins');
});

it('handles advantage for player 2', function () {
    $this->tennisGame->player1_points = 3;
    $this->tennisGame->player2_points = 4;

    $score = $this->service->getScore();

    expect($score)->toBe('Advantage Player 2');
});

it('handles win for player 2', function () {
    $this->tennisGame->player1_points = 3;
    $this->tennisGame->player2_points = 5;

    $score = $this->service->getScore();

    expect($score)->toBe('Player 2 Wins');
});

it('updates player 1 score randomly (0 or 1 increment)', function () {
    $increment = rand(0, 1);

    $this->service->updateScore('player1', $increment);
    $this->tennisGame->refresh();

    expect($this->tennisGame->player1_points)->toBe($increment)
        ->and($this->tennisGame->current_player)->toBe('player2');
});

it('throws exception if invalid increment is passed', function () {
    $this->service->updateScore('player1', 2);
})->throws(InvalidArgumentException::class, 'Increment must be either 0 or 1');

it('throws exception if trying to score after game is over', function () {
    $this->tennisGame->winner = 'Player 1';

    $this->service->updateScore('player1', 1);
})->throws(Exception::class, 'Game is already over');

it('correctly handles tied score below deuce', function () {
    $this->tennisGame->player1_points = 1;
    $this->tennisGame->player2_points = 1;

    $score = $this->service->getScore();

    expect($score)->toBe('Fifteen All');
});
