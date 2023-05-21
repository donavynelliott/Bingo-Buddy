<?php

namespace Tests\Feature\BingoBoard;

use App\Models\BingoBoard;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateBingoBoardTest extends TestCase
{
    use RefreshDatabase;

    // The squares that will be used to test the board
    protected $squares = [
        ['square 1', 'square 2', 'square 3'],
        ['square 4', 'square 5', 'square 6'],
        ['square 7', 'square 8', 'square 9']
    ];

    protected $board = null;

    protected $user;

    /**
     * Setup the test so that there is a board already available
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->board = new BingoBoard();
        $this->board->user_id = $this->user->id;
        $this->board->name = "Test Board";
        $this->board->size = 3;
        $this->board->type = "classic";

        $this->board->save();
    }

    /**
     * Test that the board cannot be updated by a user that is not the owner
     */
    public function test_board_cannot_be_updated_by_user_that_is_not_owner(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Post the squares data to the update endpoint for bingo boards
        $response = $this->post('/dashboard/boards/update/' . $this->board->id, [
            'squares' => $this->squares,
            'type' => 'blackout'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that the board squares and board type can be submitted
     */
    public function test_board_squares_can_be_submitted(): void
    {
        $this->actingAs($this->user);

        // Post the squares data to the update endpoint for bingo boards
        $response = $this->post('/dashboard/boards/update/' . $this->board->id, [
            'squares' => $this->squares,
            'type' => 'blackout',
            'name' => 'New test Board'
        ]);

        // Assert that the board was created successfully
        $response->assertRedirect('/dashboard/boards/' . $this->board->id);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bingo_boards', [
            'name' => 'New test Board',
            'user_id' => $this->board->user_id,
            'size' => $this->board->size,
            'squares' => json_encode($this->squares),
            'type' => 'blackout'
        ]);
    }

    /**
     * Test that the board squares are rendered correctly
     */
    public function test_board_squares_are_rendered_correctly(): void
    {
        $this->actingAs($this->user);
        
        // Visit the page of the previously created board
        $response = $this->get('/dashboard/boards/' . $this->board->id);

        // We should see all the values from the squares array
        foreach ($this->squares as $row) {
            foreach ($row as $square) {
                $response->assertSee($square);
            }
        }
    }
}