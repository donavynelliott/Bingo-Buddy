<?php

namespace Tests\Feature\Event\Rules;

use App\Models\Event;
use App\Models\EventRules;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRulesTest extends TestCase
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
     * An EventRules object can be instantiated.
     */
    public function test_event_rules_object_can_be_instantiated(): void
    {
        $eventRules = new EventRules();

        $this->assertInstanceOf(EventRules::class, $eventRules);
    }

    /**
     * An EventRules object can save attributes.
     */
    public function test_event_rules_object_can_save_attributes(): void
    {
        $event = new Event();
        $event->name = "Test Event";
        $event->user_id = $this->user->id;
        $event->save();

        $eventRules = new EventRules();
        $eventRules->event_id = $event->id;
        $eventRules->start_date = now()->addDays(7);
        $eventRules->end_date = now()->addDays(14);
        $eventRules->end_condition = 'end_date';
        $eventRules->max_users = 10;
        $eventRules->public = true;

        $eventRules->save();

        $this->assertDatabaseHas('event_rules', [
            'event_id' => $event->id,
            // Date formatted as YYYY-MM-DD
            'start_date' => date('Y-m-d', strtotime(now()->addDays(7))),
            'end_date' => date('Y-m-d', strtotime(now()->addDays(14))),
            'end_condition' => 'end_date',
            'max_users' => 10,
            'public' => 1,
        ]);
    }
}