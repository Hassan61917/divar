<?php

namespace App\Enums;

enum AdsStatus: string
{
    case Draft = "Draft";
    case Completed = "Completed";
    case Published = "Published";
}
