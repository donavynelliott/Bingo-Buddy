<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class EventUsersTest extends TestCase
{
    /**
     * Test an event can have multiple users up to its maximum capacity
     */
    public function test_event_can_have_multiple_users(): void
    {
        $users = User::factory()->count(10)->create();

        // Create an event
        $event = Event::factory()->create([
            'user_id' => $users[0]->id,
            'name' => 'TestEventUserRelation',
        ]);

        $rules = $event->rules()->first();
        $rules->max_users = 10;
        $rules->save();

        // Attach the users to the event
        $event->users()->attach($users);

        // Assert that the event has the users
        $this->assertTrue($event->users()->exists());

        // Assert that another user cannot be added to the event
        $new_user = User::factory()->create();
        $this->actingAs($new_user);
        $response = $this->post('/dashboard/events/' . $event->id . '/join', [
            'user_id' => $new_user->id,
        ]);
        $response->assertRedirect('/dashboard/events/' . $event->id);
        $response->assertSessionHas('error', 'The event is full!');
    }
}