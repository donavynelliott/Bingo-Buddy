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
    const NotStarted =   0;
    const InProgress =   1;
    const Closed =       2;
    const Ended =        3;
    const Archived =     4;
}
