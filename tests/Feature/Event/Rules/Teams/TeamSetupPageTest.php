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
}