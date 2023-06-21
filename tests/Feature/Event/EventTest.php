<?php

namespace Tests\Unit;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventRules;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

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
        $event->status = EventStatus::Ended;


        $event->save();

        $this->assertDatabaseHas('events', [
            'name' => 'Test Event',
            'user_id' => $this->user->id,
            'status' => EventStatus::Ended,
        ]);
    }

    /**
     * Test that an event model can have users
     */
    public function test_event_model_can_have_users(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        // Attach a user to the event
        $event->users()->attach($this->user->id);

        // Assert that the user is attached to the event
        $this->assertDatabaseHas('event_user', [
            'event_id' => $event->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertTrue($event->users->contains($this->user));
    }

    /**
     * Test that an event has a default status of Setup
     */
    public function test_event_model_has_default_status_of_setup(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
        ]);

        $this->assertTrue($event->status->is(EventStatus::Setup));
    }

    /**
     * Test than event changes its status to InProgress when the start datetime is reached
     */
    public function test_event_changes_status_to_in_progress_when_start_datetime_is_reached(): void
    {
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Event',
            'status' => EventStatus::Open,
        ]);

        $eventRules = $event->rules()->first();
        $eventRules->start_date = now()->subDay();

        $event->save();
        $eventRules->save();

        $this->assertEquals(EventStatus::Open, $event->status->value);

        // Run UpdateEventsStatuses job
        $this->artisan('app:update-event-statuses')
            ->expectsOutput('1 events updated to in progress');
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => EventStatus::InProgress,
        ]);  
    }
}