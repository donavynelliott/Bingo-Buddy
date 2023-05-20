<?php

namespace App\Http\Controllers;

use Tests\TestCase;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class JoinEventTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $event;

    /**
     * Create a user for testing purposes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'visibility' => 'public',
            'type' => 'bingo',
        ]);
    }

    /**
     * Test a user can join a public event
     */
    public function test_user_can_join_a_public_event(): void
    {
        $response = $this->get('/dashboard/events/' . $this->event->id);

        $response->assertSee("Join Event");
        $response = $this->post('/dashboard/events/' . $this->event->id . '/join');
        $response->assertRedirect('/dashboard/events/' . $this->event->id);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_user', [
            'event_id' => $this->event->id,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test a user can leave an event
     */
    public function test_user_can_leave_an_event(): void
    {
        // Attach user to event
        $this->event->users()->attach($this->user->id);
    
        $response = $this->get('/dashboard/events/' . $this->event->id);
        $response->assertSee("Leave Event");
        $response = $this->post('/dashboard/events/' . $this->event->id . '/leave');
        $response->assertRedirect('/dashboard/events/' . $this->event->id);
        $response->assertSessionHas('success');
    }
}