<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use App\Models\BingoBoard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventBingoRelationTest extends TestCase 
{
    use RefreshDatabase;

    /**
     * Test that we can access bingo boards related to an event via a pivot table
     */
    public function test_event_can_have_bingo_boards(): void
    {
        $user = User::factory()->create();

        // Create an event
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'name' => 'TestEventBingoRelation'
        ]);

        // Create a bingo board
        $bingoBoard = BingoBoard::factory()->create([
            'user_id' => $user->id,
            'name' => 'TestEventBingoRelationBoard',
            'size' => 5,
            'type' => 'classic',
        ]);

        // Attach the bingo board to the event
        $event->bingoBoards()->attach($bingoBoard);

        // Assert that the event has the bingo board
        $this->assertTrue($event->bingoBoards()->exists());
    }

    /**
     * Test that an event can have multiple bingo boards
     */
    public function test_event_can_have_multiple_bingo_boards(): void
    {
        $user = User::factory()->create();

        // Create an event
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'name' => 'TestEventBingoRelation'
        ]);

        // Create a bingo board
        $bingoBoard = BingoBoard::factory()->create([
            'user_id' => $user->id,
            'name' => 'TestEventBingoRelationBoard',
            'size' => 5,
            'type' => 'classic',
        ]);

        // Create a second bingo board
        $bingoBoard2 = BingoBoard::factory()->create([
            'user_id' => $user->id,
            'name' => 'TestEventBingoRelationBoard2',
            'size' => 5,
            'type' => 'classic',
        ]);

        // Attach the bingo board to the event
        $event->bingoBoards()->attach($bingoBoard);
        $event->bingoBoards()->attach($bingoBoard2);

        // Assert that the event has the bingo board
        $this->assertTrue($event->bingoBoards()->exists());
        $this->assertEquals($bingoBoard->id, $event->bingoBoards()->first()->id);
        $this->assertEquals($bingoBoard2->id, $event->bingoBoards()->get()[1]->id);
    }
}