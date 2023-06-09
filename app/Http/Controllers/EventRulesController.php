<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EventRulesController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $eventRules = $event->rules();
        if (!$eventRules->exists() || $event->user_id != auth()->user()->id || !$event->status->is(EventStatus::NotStarted))
        {
            abort(500);
        }

        return view('dashboard.rules.edit', [
            'event' => $event,
            'eventRules' => $event->rules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Event $event, Request $request)
    {
        // If user is not the owner of the event, abort.
        if ($event->user_id != $request->user()->id)
        {
            abort(403);
        }

        if (!$event->status->is(EventStatus::NotStarted))
        {
            abort(500);
        }

        try {
            $input = $request->validate([
                'start_date' => 'required|date_format:Y-m-d\TH:i|after:now',
                'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
                'end_condition' => 'required|in:end_date,all_boards_completed',
                'max_users' => 'required|integer|max:1000|min:2',
                'public' => 'required|boolean',
                'teams' => 'required|boolean',
            ]);

            $eventRules = $event->rules();
            if (!$eventRules->exists())
            {
                abort(500);
            }

            $eventRules->update($input);

            return redirect()->route('events.show', ['event' => $event])->with('success', 'Event rules updated successfully.');
        } catch (ValidationException $e) {
            Log::alert($e->getMessage());
            return back()->withErrors($e->errors());
        }
    }
}
