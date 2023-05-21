<?php

namespace Tests\Feature\BingoBoard;

use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BingoBoardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Create a user for testing purposes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test the model can be instantiated
     */
    public function test_bingo_board_model_can_be_instantiated(): void
    {
        $bingoBoard = new BingoBoard();

        $this->assertInstanceOf(BingoBoard::class, $bingoBoard);
    }

    /**
     * Test the model can save attributes
     */
    public function test_bingo_board_model_can_save_attributes(): void
    {
        $boardName = "Test Bingo Board";
        $boardSize = 3;
        $boardSquares = "[['square 1', 'square 2', 'square 3'],
            ['square 4', 'square 5', 'square 6'],
            ['square 7', 'square 8', 'square 9']]";

        $bingoBoard = new BingoBoard();
        $bingoBoard->user_id = $this->user->id;
        $bingoBoard->name = $boardName;
        $bingoBoard->size = $boardSize;
        $bingoBoard->squares = $boardSquares;
        $bingoBoard->save();

        $this->assertDatabaseHas('bingo_boards', [
            'name' => $boardName,
            'user_id' => $this->user->id,
            'size' => $boardSize,
            'squares' => $boardSquares
        ]);
    }
}