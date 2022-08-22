<?php

namespace App\Enums;

enum OperationType: string
{
    case deposit = "deposit";
    case withdraw = "withdraw";
}
