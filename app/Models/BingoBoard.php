<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
