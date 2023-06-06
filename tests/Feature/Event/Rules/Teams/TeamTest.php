<?php

namespace Tests\Feature\Event\Rules\Teams;

use App\Models\Event;
use App\Models\Team;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * Test that a team can be instantiated.
     */
    public function test_can_instantiate_team(): void
    {
        $event = new Event([
            'name' => 'Test Event',
            'user_id' => 1,
        ]);

        $team = new Team([
            'name' => 'Test Team',
            'event_id' => $event->id,
        ]);

        $this->assertNotNull($team);
    }

    /**
     * Test we can set attributes on a team.
     */
    public function test_can_set_attributes_on_team(): void
    {
        $event = new Event([
            'name' => 'Test Event',
            'user_id' => 1,
        ]);

        $team = new Team([
            'name' => 'Test Team',
            'event_id' => $event->id,
        ]);

        $this->assertEquals('Test Team', $team->name);
        $this->assertEquals($event->id, $team->event_id);
    }

    /**
     * Test that we can submit teams
     */
    public function test_can_submit_teams(): void
    {
        $users = \App\Models\User::factory()->count(10)->create();

        $event = new Event([
            'name' => 'Test Event',
            'user_id' => $users[0]->id,
        ]);
        
        $event->save();        

        $event->users()->attach($users);

        $this->assertEquals(10, $event->users()->count());

        $teams = [
            [
                'name' => 'Team 1',
                'users' => [
                    $users[0]->id,
                    $users[1]->id,
                ],
            ],
            [
                'name' => 'Team 2',
                'users' => [
                    $users[2]->id,
                    $users[3]->id,
                ],
            ],
            [
                'name' => 'Team 3',
                'users' => [
                    $users[4]->id,
                    $users[5]->id,
                ],
            ],
            [
                'name' => 'Team 4',
                'users' => [
                    $users[6]->id,
                    $users[7]->id,
                ],
            ],
            [
                'name' => 'Team 5',
                'users' => [
                    $users[8]->id,
                    $users[9]->id,
                ],
            ],
        ];

        $response = $this->post(route('events.teams.store', $event), [
            'teams' => $teams
        ]);

        $response->assertStatus(302);
    }
}