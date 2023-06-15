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
        'approved',
        'team_id',
        'img_link'
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

    /**
     * Find the submitted squares with the following user_id and optional team_id
     */
    public static function findWithUserAndTeam($user_id, $team_id = null)
    {
        $query = self::where('user_id', $user_id);

        if ($team_id) {
            $query->where('team_id', $team_id);
        }

        return $query->get();
    }

    /**
     * Find the submitted square with the following bingo_square_id, user_id and optional team_id
     */
    public static function findWithBingoSquareAndUserAndTeam($bingo_square_id, $user_id, $team_id = null)
    {
        $query = self::where('user_id', $user_id);

        if ($team_id) {
            $query->where('team_id', $team_id);
        }

        $query = $query->where('bingo_square_id', $bingo_square_id);

        return $query->first();
    }
}
