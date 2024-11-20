<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';

    case DONE = 'done';

    case IN_PROGRESS = 'in-progress';
}