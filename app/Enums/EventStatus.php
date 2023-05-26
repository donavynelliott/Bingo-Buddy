<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EventStatus extends Enum
{
    /**
     * @enum(description="Event has not started")
     */
    const NotStarted =   0;
    /**
     * @enum(description="Event is in progress")
     */
    const InProgress =   1;
    /**
     * @enum(description="Event has reached an automated end")
     */
    const Closed =       2;
    /**
     * @enum(description="Event has been manually ended by an event administrator")
     */
    const Ended =        3;
    /**
     * @enum(description="Event has been archived")
     */
    const Archived =     4;
}
