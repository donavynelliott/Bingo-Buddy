<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
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
     * Test that an event model can be instantiated.
     */
    public function test_event_model_can_be_instantiated(): void
    {
        $event = new Event();

        $this->assertInstanceOf(Event::class, $event);
    }

    /**
     * Test than an event model can save attributes
     */
    public function test_event_model_can_save_attributes(): void
    {
        $event = new Event();
        $event->name = "Test Event";
        $event->user_id = $this->user->id;
        $event->visibility = "public";
        $event->type = "bingo";

        $event->save();

        $this->assertDatabaseHas('events', [
            'name' => 'Test Event',
            'user_id' => $this->user->id,
            'visibility' => 'public',
            'type' => 'bingo',
        ]);
    }
}