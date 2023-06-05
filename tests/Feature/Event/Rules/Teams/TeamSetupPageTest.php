<?php

namespace Tests\Feature\Event\Rules\Teams;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamSetupPageTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $event;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->save();

        $this->event = Event::factory()->create([
            'name' => 'Test Event',
            'user_id' => $this->user->id,
        ]);

        $this->event->save();
        $rules = $this->event->rules()->first();
        $rules->teams = true;
        $rules->save();
    }

    /**
     * Test we can view the team setup page when the event type is teams and the event status is not started.
     */
    public function test_can_view_team_setup_page_when_event_type_is_teams_and_event_status_is_not_started(): void
    {      
        $this->assertTrue($this->event->status->is(EventStatus::NotStarted));
        $this->assertTrue($this->event->rules()->first()->teams);

        $this->actingAs($this->user);
        $response = $this->get(route('events.show', $this->event));
        $response->assertSee('Team Setup');

        // Follow the link
        $response = $this->get(route('events.team-setup', $this->event));
        $response->assertSee('Team Setup');
    }

    /**
     * Test we can only visit the team setup page when the event is not started and the event rules allow teams
     */
    public function test_cannot_view_team_setup_page_when_event_type_is_not_teams(): void
    {
        $this->actingAs($this->user);
        
        $rules = $this->event->rules()->first();
        $rules->teams = false;
        $rules->save();

        $this->assertTrue($this->event->status->is(EventStatus::NotStarted));
        $this->assertTrue($this->event->rules()->first()->teams == false);

        $response = $this->get(route('events.show', $this->event));
        $response->assertDontSee('Team Setup');
        $response = $this->get(route('events.team-setup', $this->event));
        $response->assertStatus(404);

        $rules->teams = true;
        $rules->save();
        $this->event->status = EventStatus::InProgress;
        $this->event->save();

        $response = $this->get(route('events.show', $this->event));
        $response->assertDontSee('Team Setup');
        $response = $this->get(route('events.team-setup', $this->event));
        $response->assertStatus(404);
    }

    /**
     * Test we can only visit the team setup page when we are the event owner
     */
    public function test_cannot_view_team_setup_page_when_not_event_owner(): void
    {
        $this->event->status = EventStatus::NotStarted;
        $this->assertTrue($this->event->status->is(EventStatus::NotStarted));
        $this->assertTrue($this->event->rules()->first()->teams);

        $this->actingAs($this->user);
        $response = $this->get(route('events.show', $this->event));
        $response->assertSee('Team Setup');

        // Change the event owner
        $user = User::factory()->create();
        $user->save();
        $this->event->user_id = $user->id;
        $this->event->save();

        $response = $this->get(route('events.show', $this->event));
        $response->assertDontSee('Team Setup');

        // Follow the link
        $response = $this->get(route('events.team-setup', $this->event));
        $response->assertStatus(404);
    }
}