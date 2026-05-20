<?php

namespace Database\Seeders;

use App\Models\Button;
use App\Models\Page;
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
            'username' => 'Jānis',
            'name' => 'Janis',
            'lname' => 'Piemers',
            'type' => UserTypes::USER->value,
            'password' => 'pass',
        ]);
        User::create([
            'username' => 'Armīns',
            'name' => 'Armīns',
            'lname' => 'Piemērs',
            'type' => UserTypes::USER->value,
            'password' => 'pass',
        ]);
        User::create([
            'username' => 'Guntars',
            'name' => 'Guntars',
            'lname' => 'Piemērs',
            'type' => UserTypes::USER->value,
            'password' => 'pass',
        ]);

        User::create([
            'username' => 'admin',
            'name' => 'rūdolfs',
            'lname' => 'ceimers',
            'type' => UserTypes::ADMIN->value,
            'password' => 'admin',
        ]);
        User::create([
            'username' => 'darbinieks',
            'name' => 'Artūrs',
            'lname' => 'Piemērs',
            'type' => UserTypes::USER->value,
            'password' => 'pass',
        ]);

        ObjectModel::create([
            'id' => 2,
            'code' => 'T100',
            'name' => 'Ielu apgaismojuma uzstāde',
        ]);
        ObjectModel::create([
            'id' => 1,
            'code' => 'Citi',
            'name' => 'Citi darbi',
        ]);

        Vehicle::create([
            'id' => 1,
            'name' => 'Ekskavators JCB',
            'usage_type' => VehicleUsageTypes::MOTOR_HOURS->value,
            'usage' => 5163.3,
        ]);
        Vehicle::create([
            'id' => 2,
            'name' => 'Ekskavators Kubota',
            'usage_type' => VehicleUsageTypes::MOTOR_HOURS->value,
            'usage' => 2960,
        ]);
        Vehicle::create([
            'id' => 3,
            'name' => 'Ekskavators Volvo',
            'usage_type' => VehicleUsageTypes::MOTOR_HOURS->value,
            'usage' => 2309.86,
        ]);
        Vehicle::create([
            'name' => 'Caurdure lielā',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 315.32,
        ]);
        Vehicle::create([
            'name' => 'Caurdure mazā',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 90.45,
        ]);
        Vehicle::create([
            'name' => 'Edgara buss',
            'usage_type' => VehicleUsageTypes::KILOMETERS->value,
            'usage' => 112726,
        ]);
        Vehicle::create([
            'name' => 'Kabeļrati jaunie',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 580.35,
        ]);
        Vehicle::create([
            'name' => 'Kabeļrati vecie',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 543,
        ]);
        Vehicle::create([
            'name' => 'Kompaktiekrāvējs Bobcat',
            'usage_type' => VehicleUsageTypes::MOTOR_HOURS->value,
            'usage' => 4248.7,
        ]);
        Vehicle::create([
            'name' => 'Pacēlājs Nissan',
            'usage_type' => VehicleUsageTypes::MOTOR_HOURS->value,
            'usage' => 9007.91,
        ]);
        Vehicle::create([
            'name' => 'Pašizgāzējs MAN',
            'usage_type' => VehicleUsageTypes::KILOMETERS->value,
            'usage' => 382256,
        ]);
        Vehicle::create([
            'name' => 'Piekabe 750kg jaunā',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 101.15,
        ]);
        Vehicle::create([
            'name' => 'Piekabe 750kg vecā ',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 910.38,
        ]);
        Vehicle::create([
            'name' => 'Pilenieka auto',
            'usage_type' => VehicleUsageTypes::KILOMETERS->value,
            'usage' => 239303,
        ]);
        Vehicle::create([
            'name' => 'Treileris jaunais',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 78.03,
        ]);
        Vehicle::create([
            'name' => 'Treileris vecais',
            'usage_type' => VehicleUsageTypes::DAYS->value,
            'usage' => 96.07,
        ]);

        Page::create([
            'name' => 'izveidot'
        ]);
        Page::create([
            'name' => 'rediget'
        ]);
        Page::create([
            'name' => 'apskatitVisus'
        ]);
        Page::create([
            'name' => 'parrekinatlaiku'
        ]);
        Page::create([
            'name' => 'atjaunotObjektus'
        ]);
        Page::create([
            'name' => 'pievienotAtskaiti'
        ]);
        Page::create([
            'name' => 'redigetAtskaiti'
        ]);
        $ApskatitAtskaites = Page::create([
            'name' => 'apskatitAtskaites'
        ]);
        $manasRez = Page::create([
            'name' => 'manasRezervacijas'
        ]);
        $sakums = Page::create([
            'name' => 'sakums'
        ]);
        Page::create([
            'name' => 'kalendars'
        ]);
        Page::create([
            'name' => 'izveidotRezervaciju'
        ]);
        $izvRL = Page::create([
            'name' => 'izveidotRezervacijuUnLietojumu'
        ]);
        $saktLiet = Page::create([
            'name' => 'saktLietojumu'
        ]);
        Page::create([
            'name' => 'beigtLietojumu'
        ]);
        Page::create([
            'name' => 'maniPabeigtieLietojumi'
        ]);
        Page::create([
            'name' => 'maniNepabeigtieLietojumi'
        ]);
        Page::create([
            'name' => 'pieteikties'
        ]);
        Page::create([
            'name' => 'public'
        ]);




        Button::create([
            'page_id' => $sakums->id,
            'name' => 'rezervet'
        ]);
        Button::create([
            'page_id' => $sakums->id,
            'name' => 'lietot'
        ]);
        Button::create([
            'page_id' => $sakums->id,
            'name' => 'lietotUnRezervet'
        ]);
        Button::create([
            'page_id' => $sakums->id,
            'name' => 'month_forward'
        ]);
        Button::create([
            'page_id' => $sakums->id,
            'name' => 'month_backward'
        ]);




        Button::create([
            'page_id' => $saktLiet->id,
            'name' => 'ne_nesakrit'
        ]);
        Button::create([
            'page_id' => $saktLiet->id,
            'name' => 'ja_sakrit'
        ]);
        Button::create([
            'page_id' => $saktLiet->id,
            'name' => 'atjaunotObjektuSarakstu'
        ]);



        Button::create([
            'page_id' => $manasRez->id,
            'name' => 'izdzest'
        ]);


        

        Button::create([
            'page_id' => $ApskatitAtskaites->id,
            'name' => 'pievienot'
        ]);
        Button::create([
            'page_id' => $ApskatitAtskaites->id,
            'name' => 'rediget'
        ]);



    }
}
