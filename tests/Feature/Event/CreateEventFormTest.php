<?php

namespace Tests\Feature\Dashboard;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventFormTest extends TestCase 
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
     * Test the create event page is rendered
     */
    public function test_create_event_page_is_rendered(): void
    {
        $response = $this->get('/dashboard/events/create');

        $response->assertStatus(200);
    }

    /**
     * Test the create event form is rendered correctly
     */
    public function test_create_event_form_is_rendered_correctly(): void
    {
        $response = $this->get('/dashboard/events/create');

        // Assert that the form is present
        $response->assertSee('Create Event');

        // Assert that all of the required fields are present
        $response->assertSee('Name');
    }

    /**
     * Test form can be submitted successfully
     */
    public function test_form_can_be_submitted_successfully(): void
    {
        $response = $this->post('/dashboard/events/store', [
            'name' => 'TestEventFormCanBeSubmittedSuccessfully',
        ]);

        $eventId = Event::where('name', 'TestEventFormCanBeSubmittedSuccessfully')->first()->id;

        // Wildcard redirect
        $response->assertRedirect('/dashboard/events/' . $eventId);
        

        $this->assertDatabaseHas('events', [
            'name' => 'TestEventFormCanBeSubmittedSuccessfully',
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test the form cannot be submitted without valid data
     */
    public function test_form_cannot_be_submitted_without_valid_data(): void
    {
        $response = $this->post('/dashboard/events/store', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
        ]);
    }
}