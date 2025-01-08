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
            self::USER->value => 'users',
            self::VEHICLE->value => 'vehicles',
            self::OBJECT->value => 'objects',
            self::REPORT->value => 'reports',
            self::VEHICLE_USE->value => 'vehicle_uses',
            self::RESERVATION->value => 'reservations',
            self::ERROR->value => 'errors',
        };
    }

    public static function GetViewName($value): string
    {   
        return match((int)$value) {
            self::USER->value => 'user',
            self::VEHICLE->value => 'vehicle',
            self::OBJECT->value => 'object',
            self::REPORT->value => 'report',
            self::VEHICLE_USE->value => 'vehicleUse',
            self::RESERVATION->value => 'reservation',
            self::ERROR->value => 'error',
        };
    }
}