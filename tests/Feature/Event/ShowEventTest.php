<?php

namespace Tests\Feature\Dashboard;

use App\Models\BingoBoard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\User;

class ShowEventTest extends TestCase
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
     * Test a user can view an event they created
     */
    public function test_user_can_view_an_event_they_created(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
        ]);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee($event->name);
        $response->assertSee(ucfirst($event->visibility));
        $response->assertSee(ucfirst($event->type));
    }

    /**
     * Test we can see the bingo boards attached to an event
     */
    public function test_can_see_bingo_boards_attached_to_an_event(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
        ]);

        $bingoBoard = BingoBoard::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Bingo Board',
            'size' => 5,
            'type' => 'classic',
        ]);

        $event->bingoBoards()->attach($bingoBoard);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee('Test Bingo Board');
    }

    /**
     * Test we can submit a board to be attached to an event
     */
    public function test_can_submit_board_to_be_attached_to_an_event(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
        ]);

        $event->save();

        $bingoBoard = BingoBoard::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Bingo Board',
            'size' => 5,
            'type' => 'classic',
        ]);

        $bingoBoard->save();

        $response = $this->post('/dashboard/events/' . $event->id . '/boards', [
            'bingo_board_ids' => [$bingoBoard->id],
        ]);

        $response->assertStatus(302);

        $response->assertRedirect('/dashboard/events/' . $event->id);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_board', [
            'event_id' => $event->id,
            'bingo_board_id' => $bingoBoard->id,
        ]);
    }

    /**
     * Test that a user can only attach an board that is theirs to an event that is theirs
     */
    public function test_user_can_only_attach_board_that_is_theirs_to_event_that_is_theirs(): void
    {
        // First make two users: A & B
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Let User A create an eventA and boardA
        $eventA = Event::factory()->create([
            'user_id' => $userA->id,
            'name' => 'eventA',
        ]);
        $eventA->save();

        $boardA = BingoBoard::factory()->create([
            'user_id' => $userA->id,
            'name' => 'boardA',
            'size' => 3,
        ]);
        $boardA->save();

        // Let User B create an eventB and boardB
        $eventB = Event::factory()->create([
            'user_id' => $userB->id,
            'name' => 'eventB',
        ]);
        $eventB->save();

        $boardB = BingoBoard::factory()->create([
            'user_id' => $userB->id,
            'name' => 'boardB',
            'size' => 3,
        ]);
        $boardB->save();

        $this->actingAs($userA);
        // Let User A try to attach boardB to eventA (should fail)
        $response = $this->post('/dashboard/events/' . $eventA->id . '/boards', [
            'bingo_board_ids' => [$boardB->id],
        ]);
        $response->assertStatus(403);

        // Let User A try to attach boardA to eventA (should succeed)
        $response = $this->post('/dashboard/events/' . $eventA->id . '/boards', [
            'bingo_board_ids' => [$boardA->id],
        ]);
        $response->assertStatus(302);

        // Let User A try to attach boardA to eventB (should fail)
        $response = $this->post('/dashboard/events/' . $eventB->id . '/boards', [
            'bingo_board_ids' => [$boardA->id],
        ]);
        $response->assertStatus(403);
    }
}