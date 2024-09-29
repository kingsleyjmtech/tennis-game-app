<div
    class="flex flex-col items-start gap-12 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center mb-4">Tennis Game</h1>

        <div class="text-center mb-6">
            <p class="text-xl font-semibold">Score: {{ $score }}</p>
            @if (!$tennisGame->winner)
                <p class="text-sm">Current Player: {{ $currentPlayer }}</p>
            @endif
        </div>

        @if (!$tennisGame->winner)
            <div class="flex justify-center gap-4">
                <button
                    wire:click="playerOneScores"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @if ($currentPlayer !== 'player1') disabled @endif
                >
                    Player 1 Scores
                </button>

                <button
                    wire:click="playerTwoScores"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    @if ($currentPlayer !== 'player2') disabled @endif
                >
                    Player 2 Scores
                </button>
            </div>

            <div class="text-center mt-4">
                <button
                    wire:click="resetGame"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mt-4"
                >
                    Reset Game
                </button>
            </div>
        @else
            <div class="text-center mt-4">
                <p class="text-lg font-semibold text-red-500">Game Over! {{ $score }}</p>
                <button
                    wire:click="createNewGame"
                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded mt-4"
                >
                    Start New Game
                </button>
            </div>
        @endif
    </div>
</div>
