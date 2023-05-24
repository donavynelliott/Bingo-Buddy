<?php

namespace Tests\Feature\Event;

use App\Models\BingoBoard;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the edit form is rendered correctly
     */
    public function test_edit_form_is_rendered_correctly(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
            'user_id' => $user->id,
        ]);
        $this->actingAs($user);

        $response = $this->get('/dashboard/events/' . $event->id . '/edit');

        $response->assertStatus(200);

        $response->assertSee('Name');
        $response->assertSee('Visibility');
        $response->assertSee('Public');
        $response->assertSee('Private');
    }

    /**
     * Test the edit displays checkboxes for all owned boards
     */
    public function test_edit_displays_checkboxes_for_all_owned_boards(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
            'user_id' => $user->id,
        ]);
        $board1 = BingoBoard::factory()->create([
            'name' => 'Test Board 1',
            'user_id' => $user->id,
            'size' => 5,
            'type' => 'classic',
        ]);
        $board2 = BingoBoard::factory()->create([
            'name' => 'Test Board 2',
            'user_id' => $user->id,
            'size' => 5,
            'type' => 'classic',
        ]);
        $board1->save();
        $board2->save();
        $event->bingoBoards()->attach($board1);
        $event->bingoBoards()->attach($board2);
        $this->actingAs($user);

        $response = $this->get('/dashboard/events/' . $event->id . '/edit');

        $response->assertStatus(200);

        $response->assertSee('Test Board 1');
        $response->assertSee('Test Board 2');
    }

    /**
     * Test the board checkboxes are checked, when board are attached to the event
     */
    public function test_board_checkboxes_are_checked_when_board_are_attached_to_the_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
            'user_id' => $user->id,
        ]);
        $board1 = BingoBoard::factory()->create([
            'name' => 'Test Board 1',
            'user_id' => $user->id,
            'size' => 5,
            'type' => 'classic',
        ]);
        $board2 = BingoBoard::factory()->create([
            'name' => 'Test Board 2',
            'user_id' => $user->id,
            'size' => 5,
            'type' => 'classic',
        ]);
        $event->bingoBoards()->attach($board1);
        $event->bingoBoards()->attach($board2);
        $this->actingAs($user);

        $response = $this->get('/dashboard/events/' . $event->id . '/edit');

        $response->assertStatus(200);

        $response->assertSee('checked');
        $response->assertSee('Test Board 2');
    }
}