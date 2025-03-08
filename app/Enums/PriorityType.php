<?php

namespace App\Enums;

enum PriorityType: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Emergency = 4;
}
