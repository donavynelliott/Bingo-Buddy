<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'status'];

    protected $attributes = [
        'status' => EventStatus::Setup
    ];

    protected $casts = [
        'status' => EventStatus::class
    ];

    /**
     * The users that belong to the event.
     */
    public function users(): BelongsToMany
    {
        // Get the related Event_User pivot model
        return $this->belongsToMany(User::class)->using(Event_User::class);
    }

    /**
     * The boards that belong to the event
     */
    public function bingoBoards(): BelongsToMany
    {
        // Get the related Event_Board pivot model
        return $this->belongsToMany(BingoBoard::class, 'event_board')->using(Event_Board::class);
    }

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the EventRules record associated with the event.
     */
    public function rules()
    {
        $eventRules = $this->hasOne(EventRules::class);

        if (!$eventRules->exists())
        {
            $eventRules = new EventRules([
                'event_id' => $this->id,
            ]);
            $eventRules->save();
        }

        return $this->hasOne(EventRules::class);
    }

    /**
     * Get the Teams associated with the event.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get whether teams are enabled
     */
    public function getTeamsEnabledAttribute()
    {
        return $this->rules()->first()->teams;
    }
}
