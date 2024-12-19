<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'rudolfs', 
            'name' => 'rudolfs', 
            'lname' => 'ceimers', 
            'type'  => UserTypes::ADMIN,
            'password' => 'grgyl',
        ]);
    }
}
