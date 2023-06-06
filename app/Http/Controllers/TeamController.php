<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    /**
     * Get the event's team setup page
     */
    public function teamSetup(Event $event)
    {
        // Get the event's users
        $users = $event->users()->get();

        if (
            $event->status->notIn([EventStatus::NotStarted]) ||
            $event->rules()->first()->teams === false ||
            $event->user_id != auth()->id()
        ) {
            abort(404);
        }

        // Return the users
        return view('dashboard.events.team-setup', compact('users', 'event'));
    }

    /**
     * Store the event's teams
     */
    public function store(Event $event)
    {
        if ($event->user_id != auth()->id()) {
            abort(403);
        }

        try {
            $validated = request()->validate([
                'teams' => 'required|array',
                'teams.*' => 'required|array',
                'teams.*.name' => 'required|string|max:255',
                'teams.*.users' => 'required|array',
                'teams.*.users.*' => 'required|integer|exists:users,id',
            ]);

            // Create the teams
            foreach ($validated['teams'] as $team) {
                $teamModel = Team::create([
                    'name' => $team['name'],
                    'event_id' => $event->id,
                ]);

                // save
                $teamModel->save();

                // Attach the users to the team
                foreach ($team['users'] as $user_id) {
                    $user = User::find($user_id);
                    $teamModel->users()->attach($user);
                }
            }

            // return json object with link to event
            return response()->json([
                'message' => 'Teams created successfully',
                'redirect' => route('events.show', $event),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
    }
}
