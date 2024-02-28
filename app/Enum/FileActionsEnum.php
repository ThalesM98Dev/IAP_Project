<?php

namespace App\Enum;

use Illuminate\Validation\Rules\Enum;

enum FileActionsEnum: string
{
    case CREATE = 'create';
    case LOCK = 'lock';
    case UNLOCK = 'unlock';
    case DELETE = 'delete';
}
