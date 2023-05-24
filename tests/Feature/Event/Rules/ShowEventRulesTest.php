<?php

namespace Tests\Feature\Event\Rules;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowEventRulesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->save();
    }

    /**
     * Test we can see the EventRules when viewing the Event
     */
    public function test_event_rules_are_visible_when_viewing_event()
    {
        $this->actingAs($this->user);
        $event = Event::factory()->create([
            'name' => 'Test Event',
            'user_id' => $this->user->id,
        ]);

        $eventRules = $event->rules();

        $response = $this->get(route('events.show', $event));
        $response->assertSee($eventRules->start_date->format('F jS, Y'));
        $response->assertSee($eventRules->end_date->format('F jS, Y'));
        $response->assertSee($eventRules->end_condition === 'end_date' ? "End Date" : "Board Completion");
        $response->assertSee($eventRules->max_users);
        $response->assertSee($eventRules->public ? "Public" : "Private");
    }

}