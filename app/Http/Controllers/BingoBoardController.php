<?php

namespace App\Http\Controllers;

use App\Models\BingoBoard;
use App\Models\BingoSquare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BingoBoardController extends Controller
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
        return view('dashboard.boards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validated the board data
        $request->validate([
            'name' => 'required|max:255|string',
            'size' => 'required|integer',
            'type' => 'required|in:blackout,classic'
        ]);

        // Create the board
        $board = BingoBoard::create([
            'name' => $request->name,
            'size' => $request->size,
            'type' => $request->type,
            'user_id' => auth()->id(),
        ]);

        // Redirect to the board page
        return redirect()->route('boards.show', $board)->with('success', 'Bingo board created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BingoBoard $bingoBoard)
    {
        return view('dashboard.boards.show', compact('bingoBoard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BingoBoard $bingoBoard)
    {
        // If not the owner of the board, return a 403
        if ($bingoBoard->user_id !== auth()->id()) {
            Log::error('User ' . auth()->id() . ' attempted to edit board ' . $bingoBoard->id . ' but is not the owner');
            return response()->json(['error' => 'You are not the owner of this board'], 403);
        }

        return view('dashboard.boards.edit', compact('bingoBoard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BingoBoard $bingoBoard)
    {
        // If not the owner of the board, return a 403
        if ($bingoBoard->user_id !== auth()->id()) {
            Log::error('User ' . auth()->id() . ' attempted to update board ' . $bingoBoard->id . ' but is not the owner');
            return response()->json(['error' => 'You are not the owner of this board'], 403);
        }
        $validator = Validator::make($request->all(), [
            // Validation 1. Ensure that the submitted json object has the correct amount of rows
            'squares' => [
                'required',
                'array',
                'size:' . pow($bingoBoard->size, 2),
                'each' => function ($attr, $val) use ($bingoBoard) {
                    return Validator::make($val, [
                        'required',
                        'string',
                        'max:255',
                    ]);
                }
            ],
            'name' => 'required|max:255|string',
            'type' => 'required|in:blackout,classic'
        ]);

        if ($validator->fails()) {
            Log::error('Bingo board update failed validation');
            // Log errors
            Log::error($validator->errors());
            return redirect()->route('boards.edit', $bingoBoard)->withErrors($validator->errors());
        }

        $validated = $validator->validated();

        $currentSquares = $bingoBoard->bingoSquares()->get();

        // Update the board
        $squares = $validated['squares'];

        foreach ($squares as $position => $square) {
            if ($square === null) {
                continue;
            }

            $currentSquare = $currentSquares->where('position', $position)->first();
            if ($currentSquare) {
                $currentSquare->title = $square;
                $currentSquare->save();
            } else {
                BingoSquare::create([
                    'bingo_board_id' => $bingoBoard->id,
                    'title' => $square,
                    'position' => $position,
                ]);
            }
        }

        $bingoBoard->name = $validated['name'];
        $bingoBoard->type = $validated['type'];
        $bingoBoard->save();

        // Redirect to the board page
        return redirect()->route('boards.show', $bingoBoard)->with('success', 'Bingo board updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BingoBoard $bingoBoard)
    {
        //
    }
}
