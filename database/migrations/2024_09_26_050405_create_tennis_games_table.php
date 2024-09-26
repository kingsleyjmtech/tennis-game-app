<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tennis_games', function (Blueprint $table) {
            $table->id();
            $table->string('player1_name');
            $table->string('player2_name');
            $table->integer('player1_points')->default(0);
            $table->integer('player2_points')->default(0);
            $table->string('winner')->nullable();
            $table->string('current_player')->default('player1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tennis_games');
    }
};
