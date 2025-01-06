<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Reservation;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $startDate = strtotime('2024-01-01');
        $endDate = strtotime('2025-01-01');
        $randomTimestamp = rand($startDate, $endDate);//Random laiks šajā gadā
        $from = date('Y-m-d H:m', $randomTimestamp);
        $until = Carbon::parse($from)->copy()->addMinutes(rand(100, 2000));
        return [
            'vehicle' => 1,//Jauzstāda manuāli
            'user' => 1,//Jauzstāda manuāli
            'from' => $from, 
            'until' => $until, 
        ];
    }
}
