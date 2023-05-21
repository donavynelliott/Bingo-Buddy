<?php

namespace App\Http\Controllers;

use App\Models\BingoBoard;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BingoBoard $bingoBoard)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BingoBoard $bingoBoard)
    {
        //
    }
}
