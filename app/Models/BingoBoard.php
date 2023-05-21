<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BingoBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'squares', 'user_id'];

    protected $casts = [
        'squares' => 'array'
    ];
    
    /**
     * Get the data structure for the board. Return a new empty board if the current one is empty
     * 
     * @return array
     */
    public function getBoardData(): array
    {
        if ($this->squares === null) {
            return self::getEmptyBoard($this->size);
        } else {
            return json_decode($this->squares);
        }
    }

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
