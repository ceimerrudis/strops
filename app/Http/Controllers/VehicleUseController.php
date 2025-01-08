<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleUse;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Error;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StartVehicleUseFullData;
use App\Http\Requests\StartVehicleUseCalendarData;
use App\Http\Requests\EndVehicleUse;
use App\Http\Requests\ViewVehicleUse;
use App\Services\SharedMethods;
use App\Enums\VehicleUsageTypes;
use Carbon\Carbon;

class VehicleUseController extends Controller
{
    //Funkcija LTSK (LTSK sākums)
    public function ViewStartVehicleUsePage(StartVehicleUseCalendarData $request)
    {
        //Pieprasījums nāk no kalendāra lapas
        $data = $request->all();
        $vehicleId = $data["vehicle"];

        return SharedMethods::StartVehicleUse($vehicleId);
    }

    private function DeleteReservation($reservationId)
    {
        //Mēģina izdzēst tikko izveidoto rezervāciju gadījumā ja neizdodas izveidot lietojumu
        Reservation::find($reservationId)?->delete();
    }

    //Funkcija LTSK (LTSK nobeigums)
    public function EndOfStartVehicleUse(StartVehicleUseFullData $request)
    {
        $data = $request->validated();

        $vehicle = Vehicle::findOrFail($data['vehicle']);
        //Vispirms pārliecinās ka neviens pašlaik nelieto šo inventāru.
        $vehicleUse = VehicleUse::whereNull('until')
        ->where('vehicle', '=', $data["vehicle"])->first();

        if($vehicleUse != null) 
        {
            //Ja neatrod beigt lietojumu karogu tad novirza uz sākumlapu.
            if($data['endCurrentUsage'] == "yes")
            {
                
                if(!isset($data["usage"]))
                {
                    return redirect()->back()->withData();
                }
                $msg = $this->StopUsingVehicleLogic($vehicleUse->id, $data["usage"]);
            }
            else
            {
                AddMessage(Text(143), "k");
                return redirect("sakums");
            }
        }

        //Pārraksta nesakrītošo lietojumu
        $createError = false;
        $usageBefore = 0;
        if(isset($data["usage"]) && $data['endCurrentUsage'] != "yes")
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
            'usage_before' => $vehicle->usage,//Nesajaukt ar kļūdas usageBefore
            'from' => Carbon::now(),
        ]);
        
        //Reģistrē kļūdas situāciju – Nesakrītoši lietojumi
        if($createError)
        {
            $errorID = Error::create([
                'comment' => Text(129),
                'usage_before' => $usageBefore,
                'vehicle_use' => $newVehicleUse->id,
                'usage_after' => $data['usage'],
                'time' => Carbon::now(),
            ]);
        }

        //Pārbauda un izveido kļūdu
        $conflictingReservation = Reservation::where('from', '<=', Carbon::now())
        ->where('until', '>=', Carbon::now())
        ->where('vehicle', '=', $data['vehicle'])
        ->where('user', '!=', Auth::user()->id)
        ->select('id', 'user')
        ->first();
        //Kļūda “Lietojums  sākts citas  personas rezervācijas laikā.” 
        if($conflictingReservation !=  null){
            Error::create([
                'vehicle_use' => $newVehicleUse->id,
                'comment' => Text(130),
                'reservation' => $conflictingReservation->id,
                'time' => Carbon::now(),
            ]);    
        }

        $user = User::findOrFail(Auth::user()->id);
        $user->lastUsedObject = $data['object'];
        $user->lastUsedVehicle = $data['vehicle'];
        $user->save();
        //Ja viss veiksmīgi izdevies izveido rezervāciju ja padoti tai nepieciešamie dati
        if(isset($data["until"]))
        {
            SharedMethods::CreateReservationLogic($data['vehicle'], Carbon::now(), $data['until']);
        }

        return redirect("maniNepabeigtieLietojumi");
    }
    
    //Funkcija LTBG
    public function ViewFinishVehicleUsePage(ViewVehicleUse $request)
    { 
        $data = $request->validated();
        $vehicleUse = VehicleUse::findOrFail($data["vehicle_use"]);
        $vehicle = Vehicle::findOrFail($vehicleUse->vehicle);
        if($vehicle->usage_type == VehicleUsageTypes::DAYS->value)
        {
            //Šim nevajadzētu notikt
            AddMessage(Text(142), "warning");
            return redirect("maniNepabeigtieLietojumi");
        }
        $id = $vehicleUse->id;
        $usage_type = $vehicle->usage_type;
        return view("vehicleUseModule.finishVehicleUse", compact('id', 'usage_type'));
    }

    //Funkcija LTBG
    public function FinishVehicleUseShortcut(ViewVehicleUse $request)
    { 
        $data = $request->validated();
        $vehicleUse = VehicleUse::findOrFail($data["vehicle_use"]);
        $vehicle = Vehicle::findOrFail($vehicleUse->vehicle);
        if($vehicle->usage_type == VehicleUsageTypes::DAYS->value)
        {
            $this->StopUsingVehicleLogic($vehicleUse->id, null);//Laikam nav lietojums jo to aprēķina automātiski
            return redirect("maniNepabeigtieLietojumi");
        }
        else{
            //Šim nevajadzētu notikt
            AddMessage(Text(142), "warning");
            return redirect("maniNepabeigtieLietojumi");
        }
    }

    //Funkcija LTBG
    public function FinishVehicleUse(EndVehicleUse $request)
    { 
        $data = $request->validated();
        $msg = $this->StopUsingVehicleLogic($data['vehicle_use'], $data['usage']);
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
        $vehicle = Vehicle::findOrFail($vehicleUse->vehicle);
        $timeUsed = SharedMethods::RecalculateTimeCalculation($vehicleUse->from, $vehicleUse->until) / ((float)60);//Iegūst laiku stundās
        
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
                'vehicle_use' => $vehicleUse->id,
                'comment' => Text(134),
                'reservation' => $conflictingReservation->id,
                'time' => Carbon::now(),
            ]);       
        }
    
        if($vehicle->usage_type == VehicleUsageTypes::MOTOR_HOURS->value)
        {
            if($usage < $vehicle->usage){
                return Text(135);
            }
            if($usage - $vehicle->usage > 8 + $timeUsed)
            {
                return Text(136);
            }
            $vehicle->usage = $usage;
        }else if($vehicle->usage_type == VehicleUsageTypes::KILOMETERS->value){
            if($usage < $vehicle->usage){
                return Text(137);
            }
            if($usage - $vehicle->usage > $timeUsed * 80)
            {
                return Text(138);
            }
            $vehicle->usage = $usage;
        }else if($vehicle->usage_type == VehicleUsageTypes::DAYS->value)
        {
            $vehicle->usage += $timeUsed/(float)24;//laiks dienās
        }

        $vehicleUse->usage_after = $vehicle->usage;
        $vehicleUse->save();
        $vehicle->save();
        return  "";
    }

    //Funkcija LTAP
    public function ViewMyFinishedVehicleUsesPage(Request $request)
    { 
        $uses = VehicleUse::where('user', Auth::user()->id)->whereNotNull('until')->orderBy('from', 'desc')
        ->join("vehicles", "vehicle", "vehicles.id")
        ->join("objects", "object", "objects.id")
        ->select("vehicle_uses.*", "vehicles.name", "vehicles.usage_type", "objects.code")->get();
        return view('vehicleUseModule.myFinishedVehicleUses', compact('uses'));
    }

    //Funkcija ALTA
    public function ViewMyActiveVehicleUsesPage(Request $request)
    {   
        $uses = VehicleUse::where('user', Auth::user()->id)->where('until', null)->orderBy('from', 'desc')
        ->join("vehicles", "vehicle", "vehicles.id")
        ->join("objects", "object", "objects.id")
        ->select("vehicle_uses.*", "vehicles.name", "vehicles.usage_type", "objects.code")->get();
        return view('vehicleUseModule.myActiveVehicleUses', compact('uses'));
    }
}
