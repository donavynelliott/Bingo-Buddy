<?php

namespace Tests\Feature\Dashboard;

use App\Enums\EventStatus;
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

    /**
     * Test we can see users that have joined the event
     */
    public function test_can_see_users_that_have_joined_the_event(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        $user = User::factory()->create([
            'name' => 'Test Player',
        ]);

        $event->users()->attach($user);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee('Test Player');
    }

    /**
     * Test we can see route to view entire member list
     */
    public function test_can_see_route_to_view_entire_member_list(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        // Create 11 users
        $users = User::factory()->count(11)->create();

        // Attach 11 users to event
        $event->users()->attach($users);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertSee('And 1 more...');

        $response->assertDontSee($users[10]->name);

        $response = $this->get('/dashboard/events/' . $event->id . '/members');

        $response->assertStatus(200);

        $response->assertSee($users[10]->name);
    }

    /**
     * Test we can see the status of the event
     */
    public function test_can_see_the_status_of_the_event(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertSee('Status');
        $response->assertSee('Setup');

        $event->status = EventStatus::InProgress;
        $event->save();

        $response = $this->get('/dashboard/events/' . $event->id);

        $response->assertSee('In Progress');
    }
}