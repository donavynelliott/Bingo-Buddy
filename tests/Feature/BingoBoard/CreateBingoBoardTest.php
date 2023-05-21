<?php 

namespace Tests\Feature\BingoBoard;

use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateBingoBoardTest extends TestCase
{
    /**
     * Test the form to create events is rendered correctly
     */
    public function test_create_bingo_board_form_is_rendered_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard/boards/create');

        // Assert that the form is present
        $response->assertSee('Create Bingo Board');

        // Assert that all of the required fields are present
        $response->assertSee('Name');
        $response->assertSee('Size');
        $response->assertSee('Type');
    }

    /**
     * Test form can be submitted successfully
     */
    public function test_form_can_be_submitted_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/dashboard/boards/store', [
            'name' => 'Test Board',
            'size' => 5,
            'type' => 'blackout'
        ]);

        // Get the id of the newly created board
        $board = BingoBoard::where('name', 'Test Board')->first();

        $response->assertRedirect('/dashboard/boards/' . $board->id);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bingo_boards', [
            'name' => 'Test Board',
            'size' => 5,
        ]);
    }

    /**
     * Test form cannot be submitted with invalid data
     */
    public function test_form_cannot_be_submitted_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/dashboard/boards/store', [
            'name' => '',
            'size' => 5,
            'type' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'type']);
    }
}