<?php

namespace App\Enums;
enum VehicleUsageType: int
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

        foreach (VehicleUsageType::cases() as $case) {
            $enums[] = [
                'name' => VehicleUsageType::getName($case->value),
                'value' => $case->value,
            ];
        }
        return $enums;
    }

    public static function GetDisplayVal($type, $usage)
    {
        return match($type) {
            self::MOTOR_HOURS->value => round($usage, 2),
            self::DAYS->value => round($usage / 9, 2),
            self::KILOMETERS->value => round($usage, 2),
        };
    }

    public static function GetTrueName($type)
    {
        return match($type) {
            self::MOTOR_HOURS->value => 'motorstundas',
            self::DAYS->value => 'stundas',
            self::KILOMETERS->value => 'kilometri',                  
        };
    }
}