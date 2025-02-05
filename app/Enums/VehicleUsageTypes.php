<?php

namespace App\Enums;
enum VehicleUsageTypes: int
{
    case MOTOR_HOURS = 1;
    case DAYS = 2;
    case KILOMETERS = 3;

    public static function getName($value): string
    {
        return match($value) {
            self::MOTOR_HOURS->value => 'motorstundas',
            self::DAYS->value => 'dienas',
            self::KILOMETERS->value => 'kilometri',            
        };
    }

    public static function GetAllEnums()
    {
        $enums = [];

        foreach (VehicleUsageTypes::cases() as $case) {
            $enums[] = [
                'name' => VehicleUsageTypes::getName($case->value),
                'value' => $case->value,
            ];
        }
        return $enums;
    }

    public static function GetDisplayVal($type, $usage)
    {
        return match($type) {
            self::MOTOR_HOURS->value => round($usage, 2),
            self::DAYS->value => round($usage, 2),
            self::KILOMETERS->value => round($usage, 2),
        };
    }
}