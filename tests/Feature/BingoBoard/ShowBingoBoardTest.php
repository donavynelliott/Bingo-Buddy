<?php

namespace Tests\Feature\BingoBoard;

use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowBingoBoardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $board;

    /**
     * Create a user and board for testing purposes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        // Create a new board
        $this->board = new BingoBoard();
        $this->board->user_id = $this->user->id;
        $this->board->name = "Test Board";
        $this->board->size = 3;
        $this->board->type = "blackout";
        $this->board->save();
    }

    /**
     * Test the board shows all of its attributes
     */
    public function test_bingo_board_shows_all_of_its_attributes(): void
    {
        $response = $this->get('/dashboard/boards/' . $this->board->id);

        $response->assertStatus(200);

        $response->assertSee($this->board->name);
        $response->assertSee($this->board->size . 'x' . $this->board->size);
        $response->assertSee($this->board->type);
    }

    /**
     * Test the board displays all of its squares
     */
    public function test_bingo_board_shows_all_of_its_squares(): void
    {
        $response = $this->get('/dashboard/boards/' . $this->board->id);

        $response->assertStatus(200);

        $response->assertSee($this->board->squares);
    }

    /**
     * Test the edit button is present if the user is the owner of the board
     * and not present if the user is not the owner of the board
     */
    public function test_edit_button_is_present_if_user_is_owner(): void
    {
        $response = $this->get('/dashboard/boards/' . $this->board->id);

        $response->assertStatus(200);

        $this->assertTrue($this->user->id === $this->board->user_id);

        $response->assertSee('Edit Board');

        $user2 = User::factory()->create();

        $this->actingAs($user2);

        $response = $this->get('/dashboard/boards/' . $this->board->id);

        $response->assertStatus(200);

        $this->assertTrue($user2->id !== $this->board->user_id);

        $response->assertDontSee('Edit Board');
    }
}