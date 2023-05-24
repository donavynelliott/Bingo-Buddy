<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRules;
use Illuminate\Http\Request;

class EventRulesController extends Controller
{
    public function edit(Event $event)
    {
        return view('dashboard.rules.edit', compact('event'));
    }
}
