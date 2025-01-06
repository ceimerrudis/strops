<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\UserTypes;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    private static $counter = 1;

    public function definition()
    {
        $name = "Jhon".(string)self::$counter;
        $lname = "Doe";
        $username = strtolower($name.$lname);
        self::$counter= self::$counter+1;
        return [
            'username' => $username,
            'name' => $name,
            'lname' => $lname,
            'password' => bcrypt('parole'),
            'type' => UserTypes::USER->value, 
        ];
    }
}
