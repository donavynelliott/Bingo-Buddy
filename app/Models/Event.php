<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'visibility', 'type', 'user_id'];

    /**
     * The users that belong to the event.
     */
    public function users(): BelongsToMany
    {
        // Get the related Event_User pivot model
        return $this->belongsToMany(User::class)->using(Event_User::class);
    }
}
