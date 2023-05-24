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
        ]);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee($event->name);
        $response->assertSee(ucfirst($event->visibility));
        $response->assertSee(ucfirst($event->type));
        $response->assertSee($this->user->name);
    }

    /**
     * Test we can see the bingo boards attached to an event
     */
    public function test_can_see_bingo_boards_attached_to_an_event(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
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
     * Test event owner can see edit button
     */
    public function test_event_owner_can_see_edit_button(): void
    {
        $this->actingAs($this->user);

        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee('Edit');
    }
}