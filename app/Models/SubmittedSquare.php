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
}
