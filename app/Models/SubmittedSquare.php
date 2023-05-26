<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedSquare extends Model
{
    use HasFactory;

    protected $fillable = [
        'bingo_board_id',
        'bingo_square_id',
        'user_id',
        'approved'
    ];

    /**
     * Get the board that owns the square.
     */
    public function bingoBoard()
    {
        return $this->belongsTo(BingoBoard::class);
    }

    /**
     * Get the BingoSquare that owns the SubmittedSquare.
     */
    public function bingoSquare()
    {
        return $this->belongsTo(BingoSquare::class);
    }
}
