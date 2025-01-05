<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\VehicleUse;
use App\Models\Vehicle;
use App\Models\Error;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'rudolfs', 
            'name' => 'rudolfs', 
            'lname' => 'ceimers', 
            'type'  => UserTypes::ADMIN->value,
            'password' => 'grgyl',
        ]);
        Vehicle::create([
            'name' => 'dienis', 
            'usage_type' => VehicleUsageTypes::DAYS->value, 
            'usage' => 0, 
        ]);
        ObjectModel::create([
            'code' => 'T404', 
            'name' => 'forsi', 
            'user_in_charge' => 1,
        ]);
    }
}
