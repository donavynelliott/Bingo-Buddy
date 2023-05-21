<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        // Retrieve all events that belong to the user
        $ownedEvents = $user->events()->get();
        // Retrieve all events that the user is a member of
        $memberEvents = $user->eventsAttachedTo()->get();

        return view('dashboard', [
            'ownedEvents' => $ownedEvents,
            'memberEvents' => $memberEvents,
        ]);
    }
}
