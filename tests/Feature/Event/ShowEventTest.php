<?php

namespace Tests\Feature\Dashboard;

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
}