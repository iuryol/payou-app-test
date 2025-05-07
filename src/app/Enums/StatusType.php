<?php

namespace App\Enums;

enum StatusType:string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case REVERSED = 'reversed';
    case FAILED = 'failed';
}
