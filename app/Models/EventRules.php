<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:i:s',
        'end_date' => 'datetime:Y-m-d H:i:s',
        'public' => 'boolean',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public static function boot()
    {
        parent::boot();
        
        $startDate = now()->addDays(7)->toDateTimeString();
        $endDate = now()->addDays(38)->toDateTimeString();

        // Round each down to the 0th second
        $startDate = substr($startDate, 0, 17) . '00';
        $endDate = substr($endDate, 0, 17) . '00';

        static::creating(function ($eventRules) use ($startDate, $endDate) {
            $eventRules->start_date = $startDate;
            $eventRules->end_date = $endDate;
        });
    }
}
