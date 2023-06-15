<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Given an event id and user id, find the team the user belongs to using the Event_User pivot table
     */
    public static function findWithEventAndUser($event_id, $user_id)
    {
        Log::alert("Finding team for event");
        Log::alert($user_id);
        $teamId = DB::table('team_user')
        ->where('user_id', $user_id)
            ->join('teams', 'team_user.team_id', '=', 'teams.id')
            ->where('teams.event_id', $event_id)
            ->select('teams.id')
            ->first()
            ->id;

        // Get the team by id
        return Team::find($teamId);
    }
}
