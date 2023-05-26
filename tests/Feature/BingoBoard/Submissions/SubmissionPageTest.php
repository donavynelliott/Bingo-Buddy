<?php

namespace Tests\Feature\BingoBoard\Submissions;

use App\Models\BingoBoard;
use App\Models\BingoSquare;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class SubmissionPageTest extends TestCase
{
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
     * Test that the submission page can be accessed.
     */
    public function test_submission_page_can_be_accessed(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $response = $this->get(route('bingo.submissions.create', [
            'event' => $this->event->id,
            'board' => $this->board->id,
            'square' => $this->square->id,
        ]));

        $response->assertOk();
    }
}