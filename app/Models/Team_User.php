<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Team_User extends Pivot
{
    protected $table = 'team_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['team_id', 'user_id'];

    /**
     * The team that the user belongs to.
     */
    public function team()
    {
        // Get the team that the user belongs to
        return $this->belongsTo(Team::class);
    }

    /**
     * The user that belongs to the team.
     */
    public function user()
    {
        // Get the user that belongs to the team
        return $this->belongsTo(User::class);
    }
}
