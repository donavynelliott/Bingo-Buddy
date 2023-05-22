<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Event_Board extends Pivot
{
    use HasFactory;

    protected $table = 'event_board';

    protected $fillable = ['event_id', 'bingo_board_id', 'position'];

    protected $attributes = [
        'position' => 0,
    ];

    protected $appends = ['position'];

    public function getPositionAttribute()
    {
        return $this->position ?? $this->position = $this->where('event_id', $this->event_id)->max('position') + 1;
    }
}
