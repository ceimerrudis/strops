<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\VehicleUse;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ObjectModel;
use Illuminate\Support\Facades\Auth;

class SharedMethods
{
    //funkcija RKLT
    public static function RecalculateTimeCalculation($from, $until) : int
    { 
        $WORK_DAY_BEGINS = 7;//CONFIG
        $WORK_DAY_BEGINS_STR = "07:00";
        $WORK_DAY_ENDS = 18;
        $WORK_DAY_ENDS_STR = "18:00";

        $datetimeString = '2025-01-04T12:30:00Z';
        $from = Carbon::parse($from);
        $until = Carbon::parse($until);
        $fromStr = $from->format('H:i');
        $untilStr = $until->format('H:i');

        //Rēķinot laiku tiek summēti visi laika intervāli starp sākuma un beigu laiku kas reizē arī laika intervālā (07:00 – 18:00).
        $time = 0;//Kopējais laiks minūtēs
        
        //Gadījums kad sākuma un beigu laiks ir vienā un tai pašā dienā.
        if($from->day == $until->day && $from->month == $until->month && $from->year == $until->year){    
            $fromClipped = $from->copy();
            $untilClipped = $until->copy();
            
            if($fromStr < $WORK_DAY_BEGINS_STR)
            {
                $fromClipped->setTime($WORK_DAY_BEGINS, 0, 0);
            }
            if($untilStr > $WORK_DAY_ENDS_STR)
            {
                $untilClipped->setTime($WORK_DAY_ENDS, 0, 0);
            }
            $time = $fromClipped->diffInMinutes($untilClipped);
            if($fromClipped > $untilClipped)
            {
                //Šajā gadijumā lietojums ir pilnībā ārpus darba laika.
                //Laika starpība ir negatīva (bet diffInMinutes atgriež absolūto vērtību).
                return $time = 0;
            }
        }
        //Gadījums kad laika intervāls izplatās pa vairākām dienām.
        else {
            $fromClipped = $from->copy();
            
            if($fromStr < $WORK_DAY_BEGINS_STR)
            {
                $fromClipped->setTime($WORK_DAY_BEGINS, 0, 0);
            }
            if($fromStr > $WORK_DAY_ENDS_STR)
            {
                $fromClipped->setTime($WORK_DAY_ENDS, 0, 0);
            }
            $endOfWorkTime = $from->copy()->setTime($WORK_DAY_ENDS, 0, 0);
            $time += $endOfWorkTime->diffInMinutes($fromClipped);
            
            $untilClipped = $until->copy();
            if($untilStr > $WORK_DAY_ENDS_STR)
            {
                $untilClipped->setTime($WORK_DAY_ENDS, 0, 0);
            }
            if($untilStr < $WORK_DAY_BEGINS_STR)
            {
                $untilClipped->setTime($WORK_DAY_BEGINS, 0, 0);
            }
            $startOfWorkTime = $until->copy()->setTime($WORK_DAY_BEGINS, 0, 0);
            $time += $startOfWorkTime->diffInMinutes($untilClipped);    
            
            //Tagad kad pievienoti abi intervāla gali atliek vienīgi  pievienot pilnas stundas par katru dienu starp šiem galiem. 
            
            $fromEndOfDay = $from->copy()->setTime(23, 59, 0);
            $untilStartOfDay = $until->copy()->setTime(0, 0, 0);
            $dayCount = $fromEndOfDay->diffInDays($untilStartOfDay);//Pilno dienu skaits (neskaitot galus).
            $time += $dayCount * (60 * ($WORK_DAY_ENDS - $WORK_DAY_BEGINS));
        }

        return $time;//minūtēs
    }

    public static function CreateReservationLogic($vehicle, $from, $until)
    {
        //Vispirms pārbauda vai rezervācija netiek veikta pagātnē.    
        //addMinutes(-1) pievienots gadījumiem kad lietotājs sāk lietot un veido rezervāciju reizē
        if(Carbon::parse($from) < Carbon::now()->addMinutes(-1))
        {
            AddMessage(Text(140), "e");
            return -1;  
        }
        
        //Pārliecinās ka norādītajā laika intervālā inventāram nav rezervāciju. 
        $reservationExists = Reservation::where('vehicle', $vehicle)
        ->where(function ($query) use($from, $until) {//Jāizpildās vienai no šīm trim pārbaudēm
            $query->orwhere(function ($query) use($from, $until) {//Pārklājas ar intervāla beigu galu
                $query->where('from', '<=', $until)
                ->where('until', '>=', $until);
            })
            ->orWhere(function ($query) use($from, $until) {//Pārklājas ar intervāla sākuma galu
                $query->where('from', '<=', $from)
                ->where('until', '>=', $from);
            })
            ->orWhere(function ($query) use($from, $until) {//Pārklājas ar intervāla vidu
                $query->where('from', '>=', $from)
                ->where('until', '<=', $until);
            });
        })
        ->exists();
        //Ja pārbaude veiksmīga tad izveido rezervācijas ierakstu datu bāzē.
        if($reservationExists)
        {
            AddMessage(Text(138), "k");
            return -1;
        }
        else
        {
            $reservationId = Reservation::create([
                'user' => Auth::user()->id,
                'vehicle' => $vehicle,
                'from' => $from,
                'until' => $until,
            ]);
            
            //Izveido paziņojumu
            $vehicleObj = Vehicle::where('id', '=', $vehicle)->select('name')->first();
            $userObj = User::where('id', '=', Auth::user()->id)->select('name')->first();
            
            $monthNames = [
                1 => 'Janvāra',
                2 => 'Februāra',
                3 => 'Marta',
                4 => 'Aprīļa',
                5 => 'Maija',
                6 => 'Jūnija',
                7 => 'Jūlija',
                8 => 'Augusta',
                9 => 'Septembra',
                10 => 'Oktobra',
                11 => 'Novembra',
                12 => 'Decembra',
            ];
            
            $from = Carbon::parse($from );
            $until = Carbon::parse($until );
            $fromMonthName = $monthNames[$from->month];
            $untilMonthName = $monthNames[$until->month];
            
            $fromStr = $from->format("d. ") . $fromMonthName . $from->format(" H:i");
            $untilStr = $until->format("d. ") . $fromMonthName . $until->format(" H:i");
            AddMessage($userObj->name . ' veiksmīgi rezervējis ' . $vehicleObj->name . '  no ' . $fromStr . ' līdz ' . $untilStr . '.', "info");
            return $reservationId;
        }
    }

    public static function StartVehicleUse($vehicleId, $from = "", $until = "")
    {
        $now = Carbon::now();
        
        $messages = [];
        //Vispirms pārliecinās ka neviens pašlaik nelieto šo inventāru.
        $vehicleUse = VehicleUse::whereNull('until')
        ->where('vehicle', '=', $vehicleId)
        ->join('users', 'user', '=', 'users.id')
        ->select('user', 'users.name', 'users.lname')->first();
        if($vehicleUse != null){
            if($vehicleUse->user == Auth::user()->id){
                $messages[] = ["statuss" => "usedBySelf", "message" => Text(116)];
            }else{
                $messages[] = ["statuss" => "used", "message" => Text(117) . $vehicleUse->name . " " . $vehicleUse->lname . "." . Text(118)];
            }
        }
        
        //Pārbauda vai lietotājs ir rezervējis inventāru uz  šo brīdi. Ja jā tad pāriet uz soli 5. Ja nē tad turpina ar soli 3.
        $myReservationExists = Reservation::where('from', '<=', $now)
        ->where('until', '>=', $now)
        ->where('vehicle', '=', $vehicleId)
        ->where('user', '==', Auth::user()->id) 
        ->exists();
        if(!$myReservationExists){
            $conflictingReservation = Reservation::where('from', '<=', $now->copy()->addMinutes(-30))
            ->where('until', '>=', $now)
            ->where('vehicle', '=', $vehicleId)
            ->where('user', '!=', Auth::user()->id) 
            ->join('users', 'user', '=', 'users.id')
            ->select('users.name', 'users.lname', 'until')
            ->first();
            
            //Pārbauda vai kāds cits ir rezervējis šo inventāru uz šo dienu. Ja jā tad  pabrīdina lietotāju.
            if($conflictingReservation != null){
                $timeString = substr($intervalReservation->until, 11, 5);//Laiks teksta formātā
                $msg = Text(119) . $intervalReservation->name . " " . $intervalReservation->lname . Text(120) . $timeString . ".";
                $messages[] = ["statuss" => "reserved", "message" => $msg];
            }
            else
            {
                $todaysReservations = Reservation::where('from', '<=', $now)
                ->where('until', '>=', $now)
                ->where('vehicle', '=', $vehicleId)
                ->where('user', '!=', Auth::user()->id) 
                ->join('users', 'user', '=', 'users.id')
                ->select('users.name', 'users.lname', 'until')
                ->first();
                if($todaysReservations != null){
                    $timeString = substr($intervalReservation->from, 11, 5);//Laiks teksta formātā
                    $msg = Text(122) . $intervalReservation->name . " " . $intervalReservation->lname . Text(121) . $timeString . ".";
                    $messages[] = ["statuss" => "reservedInFuture", "message" => $msg];
                }
            }
        }
    
        $vehicle = Vehicle::findOrFail($vehicleId);
        $usage = $vehicle->usage;
        $usage_type = $vehicle->usage_type;
        $vehicleId = $vehicle->id;
        $vehicleName = $vehicle->name;
        $objects = ObjectModel::get();
        //Iegūst Objektu kurā tiks strādāts kā arī komentāru ja objekts ir “Citi”.
        //Ja izvēlētā inventāra lietojuma  veids ir nolasāms, tad iegūst lietojuma apstiprinājumu vai lietojuma daudzumu. 
        return view("vehicleUseModule.startVehicleUse", compact('messages', 'objects', 'vehicleName', 'vehicleId', 'usage', 'usage_type', 'from', 'until'));
    }
}