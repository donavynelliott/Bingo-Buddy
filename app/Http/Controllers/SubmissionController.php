<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\BingoBoard;
use App\Models\BingoSquare;
use App\Models\Event;
use App\Models\SubmittedSquare;
use App\Models\Team;
use App\Rules\ValidBingoLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SubmissionController extends Controller
{
    public function create(Event $event, BingoSquare $bingoSquare)
    {
        return view('dashboard.submissions.create', [
            'bingoSquare' => $bingoSquare,
            'event' => $event,
        ]);
    }

    public function board(Event $event, BingoBoard $bingoBoard)
    {
        $team = null;
        if ($event->getTeamsEnabledAttribute())
        {
            $team = Team::findWithEventAndUser($event->id, auth()->user()->id);
        }

        $submittedSquares = SubmittedSquare::findWithUserAndTeam(auth()->id(), $team ? $team->id : null);
        
        return view('dashboard.submissions.board', [
            'bingoBoard' => $bingoBoard,
            'event' => $event,
            'submittedSquares' => $submittedSquares,
        ]);
    }

    public function store(Request $request, Event $event, BingoSquare $bingoSquare)
    {
        // make sure event is InProgress
        if (!$event->status->is(EventStatus::InProgress)) {
            return redirect()->back()->with('error', 'This event is not in progress!');
        }
        // make sure user is in event
        if (!$event->users()->where('user_id', auth()->id())->first()) {
            return redirect()->back()->with('error', 'You are not a member of this event!');
        }

        try {
            $input = $request->validate([
                'img_link' => ['required', 'url', 'string', new ValidBingoLink(['i.imgur.com', 'imgur.com', 'ibb.co', 'imgbb.com', 'i.ibb.co', 'postimg.cc'])],
            ]);

            $teams = $event->rules()->first()->teams;
            $team = null;

            if ($teams) {
                $team = Team::findWithEventAndUser($event->id, auth()->id());
            }

            // Check if a submitted square already exists for this user and possibly team
            $existingSubmission = SubmittedSquare::findWithBingoSquareAndUserAndTeam($bingoSquare->id, auth()->id(), $team ? $team->id : null);

            if ($existingSubmission) {
                return redirect()->route('events.show', $event)->withErrors(['img_link' => 'You have already submitted this square!']);
            }

            $submission = SubmittedSquare::create([
                'bingo_board_id' => $bingoSquare->bingoBoard->id,
                'bingo_square_id' => $bingoSquare->id,
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'team_id' => $team ? $team->id : null,
                'img_link' => $input['img_link'],
            ]);

            return redirect()->route('submissions.board', ['event' => $event, 'bingoBoard' => $bingoSquare->bingoBoard])->with('success', 'Bingo square submitted successfully!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function show(Event $event, BingoSquare $bingoSquare, SubmittedSquare $submittedSquare)
    {
        return view('dashboard.submissions.show', [
            'bingoSquare' => $bingoSquare,
            'submittedSquare' => $submittedSquare,
            'event' => $event,
        ]);
    }
}
