<?php

namespace App\Http\Controllers;

use App\Models\BingoBoard;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BingoBoard $bingoBoard)
    {
        $validator = Validator::make($request->all(), [
            // Validation 1. Ensure that the submitted json object has the correct amount of rows
            'squares' => [
                'required',
                'array',
                'size:' . $bingoBoard->size,
                'each' => function ($attribute, $value) use ($bingoBoard) {
                    // Validation 2. Ensure there is the correct amount of cells and that each cell in the json object is a string  
                    return Validator::make($value, [
                        'required',
                        'array',
                        'size:' . $bingoBoard->size,
                        'each' => 'string|max:255'
                    ]);
                }
            ],
            'type' => 'required|in:blackout,classic'
        ]);

        if ($validator->fails()) {
            Log::error('Bingo board update failed validation');
            // Log errors
            Log::error($validator->errors());
            return redirect()->route('boards.show', $bingoBoard)->withErrors($validator->errors());
        }

        // Update the board
        $bingoBoard->squares = json_encode($request->squares);
        $bingoBoard->type = $request->type;
        $bingoBoard->save();

        // Log the board was saved
        Log::info('Bingo board updated successfully');

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
