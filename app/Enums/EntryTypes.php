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
    
    public static function GetName($value): string
    {   
        return match((int)$value) {
            self::USER->value => 'user',
            self::VEHICLE->value => 'vehicle',
            self::OBJECT->value => 'object',
            self::REPORT->value => 'report',
            self::VEHICLE_USE->value => 'vehicleUse',
            self::RESERVATION->value => 'reservation',
            self::ERROR->value => 'error',
            default => ""
        };
    }
}