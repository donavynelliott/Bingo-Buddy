<?php

namespace Tests\Feature\BingoBoard\Squares;

use App\Models\BingoBoard;
use App\Models\BingoSquare;
use App\Models\Event;
use App\Models\SubmittedSquare;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmittedSquareTest extends TestCase
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
     * Test that a submitted square can be instantiated.
     */
    public function test_submitted_square_can_be_instantiated(): void
    {
        $submittedSquare = SubmittedSquare::factory()->create(
            [
                'bingo_board_id' => $this->board->id,
                'bingo_square_id' => $this->square->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertInstanceOf(SubmittedSquare::class, $submittedSquare);
    }

    /**
     * Test that a submitted square's attributes can be set.
     */
    public function test_submitted_square_attributes_can_be_set(): void
    {
        $submittedSquare = new SubmittedSquare([
            'bingo_board_id' => $this->board->id,
            'bingo_square_id' => $this->square->id,
            'user_id' => $this->user->id,
            'approved' => true,
        ]);

        $this->assertEquals($this->board->id, $submittedSquare->bingo_board_id);
        $this->assertEquals($this->square->id, $submittedSquare->bingo_square_id);
        $this->assertEquals($this->user->id, $submittedSquare->user_id);
        $this->assertEquals(true, $submittedSquare->approved);
    }

    /**
     * Test that we can retrieve the BingoBoard the SubmittedSquare belongs to.
     */
    public function test_submitted_square_belongs_to_bingo_board(): void
    {
        $submittedSquare = SubmittedSquare::factory()->create(
            [
                'bingo_board_id' => $this->board->id,
                'bingo_square_id' => $this->square->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertNotNull($submittedSquare->bingoBoard);
    }

    /**
     * Test that we can retrieve the BingoSquare the SubmittedSquare belongs to.
     */
    public function test_submitted_square_belongs_to_bingo_square(): void
    {
        $submittedSquare = SubmittedSquare::factory()->create(
            [
                'bingo_board_id' => $this->board->id,
                'bingo_square_id' => $this->square->id,
                'user_id' => $this->user->id,
            ]
        );

        $this->assertNotNull($submittedSquare->bingoSquare);
    }
}