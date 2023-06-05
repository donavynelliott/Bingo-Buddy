<?php

namespace Tests\Feature\Event\Rules;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditEventRulesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $event;

    protected $eventRules;

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

        $this->eventRules = $this->event->rules();
    }

    /**
     * Test the event rules edit page is rendered correctly
     */
    public function test_event_rules_edit_page_is_rendered_correctly()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('event-rules.edit', $this->event));

        $response->assertStatus(200);
        $response->assertSee('Edit Event Rules');
    }

    /**
     * Test the event rules edit form is rendered correctly
     */
    public function test_event_rules_edit_form_is_rendered_correctly()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('event-rules.edit', $this->event));

        $response->assertSee("End Condition");
        $response->assertSee("Start Date");
        $response->assertSee("End Date");
        $response->assertSee("Max Users");
        $response->assertSee("Teams");
        $response->assertSee($this->event->public ? "Public" : "Private");
    }

    /**
     * Test the event rules form is submitted correctly
     */
    public function test_event_rules_form_is_submitted_correctly()
    {
        $this->actingAs($this->user);

        $startDate = now()->addDays(1)->format('Y-m-d\TH:i');
        $endDate = now()->addDays(2)->format('Y-m-d\TH:i');

        $response = $this->post(route('event-rules.update', $this->event), [
            'end_condition' => 'end_date',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_users' => 12,
            'public' => true,
            'teams' => true,
        ]);

        $response->assertRedirect(route('events.show', $this->event));
        $response->assertSessionHas('success', 'Event rules updated successfully.');

        $this->assertDatabaseHas('event_rules', [
            'event_id' => $this->event->id,
            'end_condition' => 'end_date',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_users' => 12,
            'public' => true,
            'teams' => true,
        ]);
    }
}