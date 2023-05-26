<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bingo_squares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bingo_board_id')->constrained();
            $table->string('title');
            $table->text('content')->nullable();
            $table->integer('position');
            $table->timestamps();

            $table->unique(['bingo_board_id', 'position']);
        });

        Schema::create('submitted_squares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bingo_board_id')->constrained();
            $table->foreignId('bingo_square_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_squares');
        Schema::dropIfExists('bingo_squares');
    }
};
