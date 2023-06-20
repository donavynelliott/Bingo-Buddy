<?php

namespace App\Console\Commands;

use App\Enums\EventStatus;
use App\Models\BingoBoard;
use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check each event that has not started and check if the start date is in the past
        $events = Event::where('status', EventStatus::NotStarted)
            ->join('event_rules', 'events.id', '=', 'event_rules.event_id')
            ->whereDate('start_date', '<=', now())
            ->select('events.id')
            ->get();

        if ($events->count() > 0) {
            // Output the number of events that were updated
            $this->info($events->count() . ' events updated to in progress');

            Event::where('status', EventStatus::NotStarted)
                ->join('event_rules', 'events.id', '=', 'event_rules.event_id')
                ->whereDate('start_date', '<=', now())
                ->update(['status' => EventStatus::InProgress]);
        }

        // Check each event board and make sure any empty squares are filled
        foreach ($events as $event) {
            foreach ($event->bingoBoards()->get() as $bingoBoard) {
                $emptySquares = $bingoBoard->getEmptySquares();
                $bingoBoard->bingoSquares()->saveMany($emptySquares);
            }
        }

        // Check each event that has an end_condition of end_date and check if the end date is in the past
        Event::where('status', EventStatus::InProgress)
            ->join('event_rules', 'events.id', '=', 'event_rules.event_id')
            ->where('end_condition', 'end_date')
            ->whereDate('end_date', '<=', now())
            ->update(['status' => EventStatus::Ended]);
    }
}
