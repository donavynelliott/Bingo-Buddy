<?php

namespace Tests\Feature\BingoBoard;

use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditBingoBoardTest extends TestCase
{
    protected static $squares = [
        ['square 1', 'square 2', 'square 3'],
        ['square 4', 'square 5', 'square 6'],
        ['square 7', 'square 8', 'square 9']
    ];

    /**
     * Test that a user that is not the owner of the board cannot edit it
     */
    public function test_user_that_is_not_owner_cannot_edit_board(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $board = new BingoBoard();
        $board->user_id = $user->id;
        $board->name = "Test Board";
        $board->size = 3;
        $board->type = "blackout";
        $board->save();

        $user2 = User::factory()->create();
        $this->actingAs($user2);

        $response = $this->get('/dashboard/boards/edit/' . $board->id);

        $response->assertStatus(403);
    }
    /**
     * Test the board edit form is rendered correctly
     */
    public function test_edit_bingo_board_form_is_rendered_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $board = new BingoBoard();
        $board->user_id = $user->id;
        $board->name = "Test Board";
        $board->size = 3;
        $board->type = "blackout";
        $board->save();

        $response = $this->get('/dashboard/boards/edit/' . $board->id);

        // Assert that the form is present
        $response->assertSee('Edit Test Board');

        // Assert that all of the required fields are present
        $response->assertSee('Name');
        $response->assertSee('Type');
        
        // Check that we can view the squares
        $response->assertSee('square 1');
    }


    /**
     * Test the edit form can be submitted successfully
     */
    public function test_edit_form_can_be_submitted_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $board = new BingoBoard();
        $board->user_id = $user->id;
        $board->name = "Test Board";
        $board->size = 3;
        $board->type = "blackout";
        $board->save();

        $response = $this->post('/dashboard/boards/update/' . $board->id, [
            'name' => 'Test Board',
            'type' => 'blackout',
            'squares' => self::$squares
        ]);

        $response->assertRedirect('/dashboard/boards/' . $board->id);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bingo_boards', [
            'name' => 'Test Board',
            'type' => 'blackout',
            'squares' => json_encode(self::$squares)
        ]);
    }

    /**
     * Test the edit form cannot be submitted with invalid data
     */
    public function test_edit_form_cannot_be_submitted_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $board = new BingoBoard();
        $board->user_id = $user->id;
        $board->name = "Test Board";
        $board->size = 3;
        $board->type = "blackout";
        $board->save();

        $response = $this->post('/dashboard/boards/update/' . $board->id, [
            'name' => '',
            'type' => 'blackout'
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}