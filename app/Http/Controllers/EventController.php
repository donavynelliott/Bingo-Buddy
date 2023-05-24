<?php

namespace App\Http\Controllers;

use App\Models\BingoBoard;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'name' => 'required|max:255|string',
            'visibility' => 'required|in:public,private|string',
            'type' => 'required|in:bingo,raffle|string',
        ]);

        // Create the event
        $event = Event::create([
            'name' => $request->name,
            'visibility' => $request->visibility,
            'type' => $request->type,
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

        return view('dashboard.events.show', compact('event', 'bingoBoards'));
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

        // If user doesn't own the event return 403
        if ($event->user_id != auth()->id()) {
            Log::alert('User does not own event', [
                'event' => $event,
            ]);
            return abort(403);
        }

        // Validate the input
        try {
            $input = $request->validate([
                'bingo_board_ids' => 'required|exists:bingo_boards,id',
                'name' => 'required|max:255|string',
                'visibility' => 'required|in:public,private|string',
            ]);

            $boardsToAttach = [];
            Log::alert('input', [
                'input' => $input,
            ]);
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

            Log::alert('boardsToAttach', [
                'boardsToAttach' => $boardsToAttach,
            ]);

            // Attach the bingo board to the event
            foreach ($boardsToAttach as $board) {
                if (!$event->bingoBoards->contains($board)) {
                    $event->bingoBoards()->attach($board);
                }
            }

            $event->name = $input['name'];
            $event->visibility = $input['visibility'];
            $event->save();

            Log::alert('Event updated', [
                'event' => $event,
            ]);
            // Redirect to the event page
            return redirect()->route('events.show', $event)->with('success', 'Bingo board has been attached to the event!');
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
        // Get the user making the request
        $user = auth()->user();

        // If the user is already in the event, redirect back
        if ($event->users->contains($user)) {
            return redirect()->back()->with('error', 'You are already in the event!');
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
     * Associate the specified bingo board with the event.
     */
    public function attachBoard(Request $request, Event $event)
    {

    }
}
