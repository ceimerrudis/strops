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

    public function definition()
    {
        $fname = $this->faker->name;
        $names = explode(" ", $fname);
        $name = $names[0];
        $lname = $names[1];
        $username = strtolower($name.$lname);
        return [
            'username' => $username,
            'name' => $name,
            'lname' => $lname,
            'password' => bcrypt('parole'),
            'type' => UserTypes::USER->value, 
        ];
    }
}
