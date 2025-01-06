<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\VehicleUsageTypes;
use App\Models\Vehicle;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition()
    {
        $names = ["žirafe", "pacēlājs", "caurdure", "piekabe", "auto"];
        $types = [VehicleUsageTypes::DAYS->value, VehicleUsageTypes::MOTOR_HOURS->value, VehicleUsageTypes::KILOMETERS->value];
        return [
            'name' => array_rand($names),
            'usage' => mt_rand() / mt_getrandmax() * 40,
            'usage_type' => array_rand($types), 
        ];
    }
}
