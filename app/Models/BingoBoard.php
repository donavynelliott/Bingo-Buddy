<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'squares', 'user_id'];

    protected $casts = [
        'squares' => 'array'
    ];

    protected $attributes = [
        'squares' => '[]'
    ];

    /**
     * Get a empty board data structure
     */
    public static function getEmptyBoard(int $size): array
    {
        $board = [];

        for ($i = 0; $i < $size; $i++) {
            $board[$i] = [];

            for ($j = 0; $j < $size; $j++) {
                $board[$i][$j] = '';
            }
        }

        return $board;
    }
}
