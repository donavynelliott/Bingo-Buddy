<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventTest extends TestCase
{
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

        $response = $this->post('/dashboard/events/' . $event->id . '/update', [
            'bingo_board_ids' => [$bingoBoard->id],
            'name' => $event->name,
            'visibility' => $event->visibility,
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
            'visibility' => 'public',
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
            'visibility' => 'public',
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
        $response = $this->post('/dashboard/events/' . $eventA->id . '/update', [
            'bingo_board_ids' => [$boardB->id],
            'name' => $eventA->name,
            'visibility' => $eventA->visibility,
        ]);
        $response->assertStatus(403);

        // Let User A try to attach boardA to eventA (should succeed)
        $response = $this->post('/dashboard/events/' . $eventA->id . '/update', [
            'bingo_board_ids' => [$boardA->id],
            'name' => $eventA->name,
            'visibility' => $eventA->visibility,
        ]);
        $response->assertStatus(302);

        // Let User A try to attach boardA to eventB (should fail)
        $response = $this->post('/dashboard/events/' . $eventB->id . '/update', [
            'bingo_board_ids' => [$boardA->id],
            'name' => $eventB->name,
            'visibility' => $eventB->visibility,
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test can submit update event form
     */
    public function test_can_submit_update_event_form(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
        ]);

        $board = BingoBoard::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Bingo Board',
            'size' => 5,
            'type' => 'classic',
        ]);

        $board->save();
        $event->save();

        $response = $this->post('/dashboard/events/' . $event->id . '/update', [
            'name' => 'Updated Event',
            'visibility' => 'private',
            'bingo_board_ids' => [$board->id],
        ]);

        $response->assertStatus(302);

        $response->assertRedirect('/dashboard/events/' . $event->id);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event',
            'visibility' => 'private',
        ]);

        $this->assertDatabaseHas('event_board', [
            'event_id' => $event->id,
            'bingo_board_id' => $board->id,
        ]);
    }
}