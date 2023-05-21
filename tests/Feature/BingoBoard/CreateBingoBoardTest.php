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
    }
}