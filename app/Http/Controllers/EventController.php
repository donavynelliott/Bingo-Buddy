<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\BingoBoard;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $select = [
            'events.id',
            'events.name',
            'events.status',
            'event_rules.start_date',
            'event_rules.end_date',
            'event_rules.end_condition',
            'event_rules.max_users',
            'users.name AS host_name',
            DB::raw('(SELECT COUNT(*) FROM event_user WHERE event_user.event_id = events.id) AS users_count'),
        ];

        $events = Event::where('status', EventStatus::Open)
            ->join('event_rules', 'events.id', '=', 'event_rules.event_id')
            ->leftJoin('users', 'events.user_id', '=', 'users.id')
            ->where('public', true)
            ->whereDate('start_date', '>', now())
            ->select($select)
            ->get();

        return view('dashboard.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $input = $request->validate([
            'name' => 'required|max:255|string',
        ]);

        // Create the event
        $event = Event::create([
            'name' => $input['name'],
            'user_id' => auth()->id(),
        ]);

        // Redirect to the event page
        return redirect()->route('events.show', $event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if ($event == null) {
            Log::error('Event not found');
            abort(404);
        }

        $bingoBoards = $event->bingoBoards()->get();
        $eventRules = $event->rules();

        return view('dashboard.events.show', compact('event', 'bingoBoards', 'eventRules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $boards = BingoBoard::where('user_id', auth()->id())->get();
        return view('dashboard.events.edit', compact(['event', 'boards']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!$event->status->is(EventStatus::Setup)) {
            abort(500);
        }

        // If user doesn't own the event return 403
        if ($event->user_id != auth()->id()) {
            return abort(403);
        }

        // Validate the input
        try {
            $input = $request->validate([
                'bingo_board_ids' => 'exists:bingo_boards,id',
                'name' => 'required|max:255|string',
            ]);

        
            if (isset($input['bingo_board_ids'])) {
                // For each bingo_board_id
                foreach ($input['bingo_board_ids'] as $bingo_board_id) {
                    $bingoBoard = BingoBoard::find($bingo_board_id);
                    // If board is null, give 500
                    if ($bingoBoard == null) {
                        abort(500);
                    }

                    // If board is not owned by the user, give 403
                    if ($bingoBoard->user_id != auth()->id()) {
                        abort(403);
                    }

                    $boardsToAttach[] = $bingoBoard;
                }

                // Attach the bingo board to the event
                foreach ($boardsToAttach as $board) {
                    if (!$event->bingoBoards->contains($board)) {
                        $event->bingoBoards()->attach($board);
                    }
                }
            }

            $event->name = $input['name'];
            $event->save();

            // Redirect to the event page
            return redirect()->route('events.show', $event)->with('success', 'Event has been updated!');
        } catch (ValidationException $e) {
            Log::error('Validation error', [
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Validation error!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }

    /**
     * Join the specified event.
     */
    public function join(Request $request, Event $event)
    {
        if (!$event->status->is(EventStatus::Open)) {
            abort(500);
        }

        // Check if the max_users has been reached
        if ($event->users()->count() >= $event->rules->max_users) {
            return redirect()->route('events.show', $event)->with('error', 'The event is full!');
        }

        // Get the user making the request
        $user = auth()->user();

        // If the user is already in the event, redirect back
        if ($event->users->contains($user)) {
            return redirect()->route('events.show', $event)->with('error', 'You are already in the event!');
        }

        // If event is public, attach the user to the event
        // Add the user to the event
        $event->users()->attach(auth()->id(), [
            'name' => $request->name,
        ]);

        // Redirect to the event page
        return redirect()->route('events.show', $event)->with('success', 'You have joined the event!');
    }

    /**
     * Leave the specified event.
     */
    public function leave(Event $event)
    {
        if (!$event->status->is(EventStatus::Open)) {
            abort(500);
        }

        // Get the user making the request
        $user = auth()->user();

        // If the user is not in the event, redirect back
        if (!$event->users->contains($user)) {
            return redirect()->back()->with('error', 'You are not in the event!');
        }

        // Remove the user from the event
        $event->users()->detach(auth()->id());

        // Redirect to the event page
        return redirect()->route('events.show', $event)->with('success', 'You have left the event!');
    }

    /**
     * Get the event's users
     */
    public function members(Event $event)
    {
        // Get the event's users
        $users = $event->users()->get();

        // Return the users
        return view('dashboard.events.members', compact('users', 'event'));
    }

    /**
     * Set the status of the event to open
     */
    public function open(Event $event)
    {
        if (!$event->status->is(EventStatus::Setup)) {
            abort(500);
        }

        // If user doesn't own the event return 403
        if ($event->user_id != auth()->id()) {
            return abort(403);
        }

        // If the event doesn't have any bingo boards, redirect back
        if ($event->bingoBoards->count() == 0) {
            return redirect()->route('events.show', $event)->withErrors('The event needs at least one bingo board!');
        }

        // Set the status of the event to open
        $event->status = EventStatus::Open;
        $event->save();

        // Redirect to the event page
        return redirect()->route('events.show', $event)->with('success', 'The event has been opened!');
    }
}
