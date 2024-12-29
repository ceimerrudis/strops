<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleUse;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StartVehicleUse;
use App\Http\Requests\StartVehicleUseCalendarData;
use App\Http\Requests\EndVehicleUse;

class VehichleUseController extends Controller
{
    //Funkcija LTSK (LTSK sākums)
    public function ViewStartVehicleUsePage(StartVehicleUseCalendarData $request)
    {
        //Pieprasījums nāk no kalendāra lapas
        $data = $request->all();
        $vehicleId = $request->$vehicle;

        $now = Carbon::now();
        
        $messages = [];
        //Vispirms pārliecinās ka neviens pašlaik nelieto šo inventāru.
        $vehicleUse = VehicleUse::whereNull('until')
        ->where('vehicle', '=', $vehicleId)
        ->join('users', 'user', '=', 'users.id')
        ->select('user', 'users.name', 'users.lname')->first();
        if($vehicleUse->user == Auth::user()->id){
            $messages[] = ["statuss" => "usedBySelf", "message" => Text(116)];
        }else{
            $messages[] = ["statuss" => "used", "message" => Text(117) . $vehicleUse->name . " " . $vehicleUse->lname . "." . Text(118)];
        }
        
        //Pārbauda vai lietotājs ir rezervējis inventāru uz  šo brīdi. Ja jā tad pāriet uz soli 5. Ja nē tad turpina ar soli 3.
        $myReservationExists = Reservation::where('from', '<=', $now)
        ->where('until', '>=', $now)
        ->where('vehicle', '=', $vehicleId)
        ->where('user', '==', Auth::user()->id) 
        ->select('users.name', 'users.lname', 'until')
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
        $usagetype = $vehicle->usagetype;
        //Iegūst Objektu kurā tiks strādāts kā arī komentāru ja objekts ir “Citi”.
        //Ja izvēlētā inventāra lietojuma  veids ir nolasāms, tad iegūst lietojuma apstiprinājumu vai lietojuma daudzumu. 
        return view("vehicleUseModule.StartVehicleUse", compact('messages', 'usage', 'usagetype'));
    }

    //Funkcija LTSK (LTSK nobeigums)
    public function StartVehicleUse(StartVehicleUse $request)
    {
        $data = $request->validated();

        //Ja norādīts ka jābeidz iepriekšējais lietojums vai ka lietojums ir nepareizs tad lietojumam JĀBŪT norādītam.
        if(($data['endCurrentUsage'] == "yes" || $data['usageCorrect'] != "yes") && !isset($data["usage"]))
        {
            return redirect("/kalendars");//TODO
        }

        $vehicle = Vehicle::findOrFail($data['vehicle']);
        //Vispirms pārliecinās ka neviens pašlaik nelieto šo inventāru.
        $vehicleUse = VehicleUse::whereNull('until')
        ->where('vehicle', '=', $data["vehicle"])->first();

        if($vehicleUse != null) 
        {
            //Ja neatrod beigt lietojumu karogu tad novirza uz sākumlapu.
            if($data['endCurrentUsage'] == "yes")
            {
                $this->StopUsingVehicleLogic($vehicleUse, $vehicle, $data["usage"]);
            }
            else
            {
                //TODO addMessage()
                return redirect("/kalendars");//TODO
            }
        }
        
        //Pārraksta nesakrītošo lietojumu
        $createError = false;
        $usageBefore = 0;
        if($data['usageCorrect'] != "yes")
        {
            if($vehicle->usage != $data['usage']){
                $createError = true;
                $usageBefore = $vehicle->usage;
                $vehicle->usage = $data['usage'];
                $vehicle->save();
            }
        }
         
        //Izveido lietojumu
        $newVehicleUse = VehicleUse::create([
            'user' => Auth::user()->id,
            'vehicle' => $data['vehicle'],
            'object' => $data['object'],
            'comment' => $data['comment'],
            'usageBefore' => $vehicle->usage,//Nesajaukt ar kļūdas usageBefore
            'from' => Carbon::now(),
        ]);

        //Reģistrē kļūdas situāciju – “Lietojums  sākts citas  personas rezervācijas laikā.” 
        if($createError)
        {
            $errorID = Error::create([
                'comment' => Text(129),
                'usagebefore' => $usageBefore,
                'vehicleUse' => $newVehicleUse->id,
                'usageafter' => $data['usage'],
                'time' => Carbon::now(),
            ]);
            //TODO send mail
        }

        //Pārbauda un izveido kļūdu
        $conflictingReservation = Reservation::where('from', '<=', Carbon::now())
        ->where('until', '>=', Carbon::now())
        ->where('vehicle', '=', $data['vehicle'])
        ->where('user', '!=', Auth::user()->id)
        ->select('id', 'user')
        ->first();
        if($conflictingReservation !=  null){
            Error::create([
                'vehicleUse' => $newVehicleUse->id,
                'comment' => "",
                'reservation' => $conflictingReservation->id,
                'time' => Carbon::now(),
            ]);    
        }

        $user = User::findOrFail(Auth::user()->id);
        $user->lastUsedObject = $data['object'];
        $user->lastUsedVehicle = $data['vehicle'];
        $user->save();

        return redirect("maniNepabeigtieLietojumi");
    }
    
    //Funkcija LTBG
    public function FinishVehichleUse(EndVehicleUse $request)
    { 
        $data = $request->validated();
        $msg = $this->StopUsingVehicleLogic($data['vehicleUse'], $data['usage']);
        if($msg != ""){
            return back()->with('msg', $msg);
        }
        return redirect("maniPabeigtieLietojumi");
    }
    
    //Funkcija LTBG
    public function StopUsingVehicleLogic($usageId, $usage)
    {  
        $vehicleUse = VehicleUse::findOrFail($usageId);
        if($vehicleUse->until != null)
        {
            return  'Šis lietojums jau ir pabeigts.';
        }
        
        //Iegūst laika ilgumu starp lietojuma sākumu un pašreizējo brīdi.
        $vehicleUse->until = Carbon::now();
        $time = AdminController->RecalculateTimeCalculation($vehicleUse->from, $vehicleUse->until) / ((float)60);//Iegūst laiku stundās
        
        //Pārbaida vai kādam citam darbiniekam pašlaik nav rezervācija uz šo inventāru.
        // Ja ir tad piefiksē kļūdas gadījumu
        $conflictingReservation = Reservation::where('from', '<=', Carbon::now())
        ->where('until', '>=', Carbon::now())
        ->where('vehicle', '=', $vehicle->id)
        ->where('user', '!=', Auth::user()->id)
        ->select('id')
        ->first();
        if($conflictingReservation != null)
        {
            Error::create([
                'vehicleUse' => $vehicleUse->id,
                'comment' => Text(134),
                'reservation' => $conflictingReservation->id,
                'time' => Carbon::now(),
            ]);       
        }
    
        if($vehicle->usagetype == VehicleUsageType::MOTOR_HOURS->value)
        {
            if($usage < $vehicle->usage){
                return Text(135);
            }
            if($usage - $vehicle->usage > 8 + $timeUsed)
            {
                return Text(136);
            }
            $vehicle->usage = $usage;
        }else if($vehicle->usagetype == VehicleUsageType::KILOMETERS->value){
            if($usage < $vehicle->usage){
                return Text(137);
            }
            if($usage - $vehicle->usage > $timeUsed * 80)
            {
                return Text(138);
            }
            $vehicle->usage = $usage;
        }else if($vehicle->usagetype == VehicleUsageType::DAYS->value)
        {
            $vehicle->usage += $timeUsed/(float)24;//laiks dienās
        }

        $vehicleUse->usageAfter = $vehicle->usage;
        $vehicleUse->save();
        $vehicle->save();
    }

    //Funkcija LTAP
    public function ViewMyFinishedVehichleUsesPage(Request $request)
    { 
        $uses = VehicleUse::where('user', Auth::user()->id)->order_by('from', 'desc')->get();
        return view('vehicleUseModule.ViewMyFinishedVehichleUsesPage', compact('uses'));
    }

    //Funkcija ALTA
    public function ViewMyActiveVehichleUsesPage(Request $request)
    { 
        $uses = VehicleUse::where('user', Auth::user()->id)->where('until', null)->order_by('from', 'desc')->get();
        return view('vehicleUseModule.ViewMyActiveVehichleUsesPage', compact('uses'));
    }
}
