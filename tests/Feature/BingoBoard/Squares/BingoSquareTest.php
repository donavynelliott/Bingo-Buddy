<?php

namespace Tests\Feature\BingoBoard\Squares;

use App\Models\BingoBoard;
use App\Models\BingoSquare;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BingoSquareTest extends TestCase
{
    use RefreshDatabase;

    protected $event, $board, $square, $user;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->event = Event::factory()->create([
            'name' => 'Test Event',
            'user_id' => $this->user->id,
        ]);
        $this->board = BingoBoard::factory()->create([
            'name' => 'Test Board',
            'user_id' => $this->user->id,
            'size' => 3,
        ]);
        $this->event->bingoBoards()->attach($this->board->id);
        $this->square = BingoSquare::factory()->create([
            'bingo_board_id' => $this->board->id,
            'title' => 'Test Square',
            'position' => 0,
        ]);
    }

    /**
     * Test that a square can be instantiated.
     */
    public function test_square_can_be_instantiated(): void
    {
        $square = new BingoSquare();

        $this->assertInstanceOf(BingoSquare::class, $square);
    }

    /**
     * Test that a square's attributes can be set.
     */
    public function test_bingo_square_attributes_can_be_set(): void
    {
        $square = new BingoSquare([
            'bingo_board_id' => $this->board->id,
            'title' => 'Test Square',
            'content' => 'Test Content',
            'position' => 1,
        ]);
        
        $this->assertEquals($this->board->id, $square->bingo_board_id);
        $this->assertEquals('Test Square', $square->title);
        $this->assertEquals('Test Content', $square->content);
        $this->assertEquals(1, $square->position);
    }
}