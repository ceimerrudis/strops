<?php

namespace App\Enums;

enum UserType: int
{
    case ADMIN = 2;
    case USER = 1;

    public static function getName($value): string
    {   
        return match($value) {
            self::ADMIN->value => 'administrators',
            self::USER->value => 'darbinieks',
        };
    }
    public static function GetAllEnums()
    {
        $enums = [];

        foreach (UserType::cases() as $case) {
            $enums[] = [
                'name' => UserType::getName($case->value),
                'value' => $case->value,
            ];
        }
        return $enums;
    }
}