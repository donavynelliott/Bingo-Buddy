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
        $boardType = 'blackout';

        $bingoBoard = new BingoBoard();
        $bingoBoard->user_id = $this->user->id;
        $bingoBoard->name = $boardName;
        $bingoBoard->size = $boardSize;
        $bingoBoard->squares = $boardSquares;
        $bingoBoard->type = $boardType;
        $bingoBoard->save();

        $this->assertDatabaseHas('bingo_boards', [
            'name' => $boardName,
            'user_id' => $this->user->id,
            'size' => $boardSize,
            'squares' => $boardSquares,
            'type' => $boardType,
        ]);
    }

    /**
     * Test the model can output a default empty board
     */
    public function test_bingo_board_model_can_output_a_default_empty_board(): void
    {
        // Test if the board outputs correctly for a 3x3 board
        $smallBoard = BingoBoard::getEmptyBoard(3);
        $this->assertEquals($smallBoard, [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ]);

        // Test if the board outputs correctly for a 6x6 board
        $largeBoard = BingoBoard::getEmptyBoard(6);
        $this->assertEquals($largeBoard, [
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
        ]);
    }

    /**
     * Test the model can output a default empty board when the currently saved board is empty
     */
    public function test_bingo_board_model_can_output_a_default_empty_board_when_the_currently_saved_board_is_empty(): void
    {
        $boardName = "Test Bingo Board";
        $boardSize = 4;

        $bingoBoard = new BingoBoard();
        $bingoBoard->user_id = $this->user->id;
        $bingoBoard->name = $boardName;
        $bingoBoard->size = $boardSize;
        $bingoBoard->save();

        $this->assertEquals(json_encode($bingoBoard->getBoardData()), json_encode([
            ['', '', '', ''],
            ['', '', '', ''],
            ['', '', '', ''],
            ['', '', '', '']
        ]));
    }
}