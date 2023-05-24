<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRules extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'start_date',
        'end_date',
        'end_condition',
        'max_users',
        'public',
    ];

    protected $attributes = [
        'end_condition' => 'end_date',
        'max_users' => 10,
        'public' => false,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($eventRules) {
            $eventRules->start_date = now()->addDays(7);
            $eventRules->end_date = now()->addDays(38);
        });
    }
}
