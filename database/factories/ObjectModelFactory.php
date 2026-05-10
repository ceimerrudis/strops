<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ObjectModel;
use App\Enums\VehicleUsageTypes;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ObjectModel>
 */
class ObjectModelFactory extends Factory
{
    protected $model = ObjectModel::class;

    public function definition()
    {
        
        $types = [VehicleUsageTypes::DAYS->value, VehicleUsageTypes::MOTOR_HOURS->value, VehicleUsageTypes::KILOMETERS->value];
        return [
            'code' => "T".((string)rand(100, 999)),
            'name' => $this->faker->text(30),
            'user_in_charge' => null, 
            'active' => true,
        ];
    }
}
