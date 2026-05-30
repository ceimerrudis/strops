<?php

namespace App\Enums;
enum VinjetTypes: int
{
    case NONE = 1;
    case REQUIRED = 2;

    public static function getName($value): string
    {
        return match($value) {
            self::NONE->value => 'nav nepieciešama',
            self::REQUIRED->value => 'ir nepieciešama',            
        };
    }

    public static function GetAllEnums()
    {
        $enums = [];

        foreach (VinjetTypes::cases() as $case) {
            $enums[] = [
                'name' => VinjetTypes::getName($case->value),
                'value' => $case->value,
            ];
        }
        return $enums;
    }
}
