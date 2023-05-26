<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoSquare extends Model
{
    use HasFactory;

    protected $fillable = [
        'bingo_board_id',
        'title',
        'content',
        'position',
    ];

    /**
     * Get the board that owns the square.
     */
    public function bingoBoard()
    {
        return $this->belongsTo(BingoBoard::class);
    }
}
