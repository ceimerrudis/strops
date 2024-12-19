<?php

namespace App\Enums;

enum EntryTypes: int
{
    case USER = 1;
    case VEHICLE = 2;
    case OBJECT = 3;
    case REPORT = 4;
    case VEHICLE_USE = 5;
    case RESERVATION = 6;
    case ERROR = 7;
}