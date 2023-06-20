<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BingoBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'user_id', 'type'];
    
    /**
     * Get all the BingoSquares for the board.
     */
    public function bingoSquares()
    {
        return $this->hasMany(BingoSquare::class);
    }

    /**
     * Get a collection of bingo squares to fill in the empty spaces of the board
     */
    public function getEmptySquares(): Collection
    {
        $bingoSquares = $this->bingoSquares()->get();
        
        $filledPositions = $bingoSquares->pluck('position')->toArray();
        $size = $this->size;

        $missingPositions = [];

        // Iterate through all the possible positions.
        for ($i = 0; $i < $size * $size; $i++) {
            // Check if the current position is not in the filled positions array.
            if (!in_array($i, $filledPositions)) {
                // Add the current position to the missing positions array.
                $missingPositions[] = $i;
            }
        }

        // Create a collection of BingoSquares with the missing positions.
        $emptySquares = collect();
        foreach ($missingPositions as $position) {
            $emptySquares->push(new BingoSquare([
                'position' => $position,
                'title' => 'Free Square',
                'content' => '',
            ]));
        }

        return $emptySquares;
    }
}
