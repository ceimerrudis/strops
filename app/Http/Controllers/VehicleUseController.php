<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleUse;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StartVehicleUse;
use App\Http\Requests\StartVehicleUseCalendarData;
use App\Http\Requests\EndVehicleUse;
use App\Http\Requests\ViewVehicleUse;
use App\Services\SharedMethods;

class VehicleUseController extends Controller
{
    //Funkcija LTSK (LTSK sākums)
    //GET
    public function ViewStartVehicleUsePage(StartVehicleUseCalendarData $request)
    {
        //Pieprasījums nāk no kalendāra lapas
        $data = $request->all();
        $vehicleId = $request->$vehicle;

        return SharedMethods::StartVehicleUse($vehicleId);
    }

    private function DeleteReservation($reservationId)
    {
        //Mēģina izdzēst tikko izveidoto rezervāciju gadījumā ja neizdodas izveidot lietojumu
        Reservation::find($reservationId)?->delete();
    }

    //Funkcija LTSK (LTSK nobeigums)
    //POST
    public function StartVehicleUse(StartVehicleUse $request)
    {
        $data = $request->validated();

        //Ja norādīts ka jābeidz iepriekšējais lietojums vai ka lietojums ir nepareizs tad lietojumam JĀBŪT norādītam.
        if(($data['endCurrentUsage'] == "yes" || $data['usageCorrect'] != "yes") && !isset($data["usage"]))
        {
            DeleteReservation($data['reservationToDelete']);
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
                AddMessage(Text(143), "k");
                DeleteReservation($data['reservationToDelete']);
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
    public function ViewFinishVehicleUsePage(ViewVehicleUse $request)
    { 
        $data = $request->validated();
        $vehicleUse = VehicleUse::findOrFail($data["vehicleUse"]);
        return view("vehicleUseModule.finishVehicleUse", compact('vehicleUse'));
    }


    //Funkcija LTBG
    public function FinishVehicleUse(EndVehicleUse $request)
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
        $time = SharedMethods::RecalculateTimeCalculation($vehicleUse->from, $vehicleUse->until) / ((float)60);//Iegūst laiku stundās
        
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
    public function ViewMyFinishedVehicleUsesPage(Request $request)
    { 
        $uses = VehicleUse::where('user', Auth::user()->id)->orderBy('from', 'desc')->get();
        return view('vehicleUseModule.myFinishedVehicleUses', compact('uses'));
    }

    //Funkcija ALTA
    public function ViewMyActiveVehicleUsesPage(Request $request)
    { 
        $uses = VehicleUse::where('user', Auth::user()->id)->where('until', null)->orderBy('from', 'desc')->get();
        return view('vehicleUseModule.myActiveVehicleUses', compact('uses'));
    }
}
