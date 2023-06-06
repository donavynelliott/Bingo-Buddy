<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'event_id'];

    /**
     * Get the event that owns the team.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the users that belong to the team via the team_user pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->using(Team_User::class);
    }
}
