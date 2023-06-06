<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Event_User extends Pivot
{
    protected $table = 'event_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'user_id'];

    /**
     * The event that the user belongs to.
     */
    public function event()
    {
        // Get the event that the user belongs to
        return $this->belongsTo(Event::class);
    }
}
