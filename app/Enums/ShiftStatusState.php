<?php

namespace App\Enums;

enum ShiftStatusState : string
{
    case MORNING = 'Morning';
    case AFTERNOON = 'Afternoon';
    case EVENING = 'Evening';
    case DAY = 'Day';
}
