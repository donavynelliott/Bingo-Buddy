<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();
    }

    /**
     * Test that we can visit the dashboard when logged in
     */
    public function test_view_dashboard_when_logged_in(): void
    {
        $this->actingAs($this->user)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee('Dashboard');
    }

    /**
     * Test that we can see events that we are the owner of
     */
    public function test_owner_can_see_events(): void
    {
        // Create an event
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'TestDashboardEventsOwner'
        ]);

        $this->actingAs($this->user)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee($event->name);
    }

    /**
     * Test that we can see events that we are a member of
     */
    public function test_member_can_see_events(): void
    {
        // Create an event
        $event = Event::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'TestDashboardEventsMember'
        ]);

        // Create a new user to be a member
        $user2 = User::factory()->create();

        // Add the user as a member
        $event->users()->attach($user2->id);

        $this->actingAs($this->user)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee($event->name);
    }
}