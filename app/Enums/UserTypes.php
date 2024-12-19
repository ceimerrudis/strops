<?php

namespace App\Enums;

enum UserTypes: int
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

        foreach (UserTypes::cases() as $case) {
            $enums[] = [
                'name' => UserTypes::getName($case->value),
                'value' => $case->value,
            ];
        }
        return $enums;
    }
}