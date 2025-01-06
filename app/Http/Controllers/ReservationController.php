<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserTypes;
use App\Models\Vehicle;
use App\Models\VehicleUse;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Services\SharedMethods;
use App\Http\Requests\GetCalendar;
use App\Http\Requests\SpecificEntry;
use App\Http\Requests\MakeReservation;
use App\Enums\EntryTypes;

class ReservationController extends Controller
{
    //Funkcijas RZKL atver kalendāra lapu
    public function ViewCalendarPage(Request $request)
    {  
        //Eksistē inventāri kurus izmanto tikai  1 konkrēta persona. 
        //Tapēc pārējiem lietotājiem (neskaitot administratorus) šie inventāri netiek rādīti.
        $vehicles = [];
        if(Auth::user()->type != UserTypes::ADMIN->value){
            $excludes = [];
            if(Auth::user()->username != "JānisS")
                $excludes[] = 425314055;
            if(Auth::user()->username != "Armīns")
                $excludes[] = 425314056;
            if(Auth::user()->username != "GuntarsG")
                $excludes[] = 425314057;
            
            $vehicles = Vehicle::whereNotIn('id', $excludes)
                ->orderBy('name', 'asc')
                ->get();
        }else{
            $vehicles = Vehicle::orderBy('name', 'asc')->get();
        }
        
        $user = Auth::user()->id;
        //Iegūst sarakstu ar pašlaik lietotajiem inventāriem un to lietotājiem
        $vehicleUses = VehicleUse::whereNull('until')
        ->join('users', 'user', 'users.id')
        ->select('vehicle_uses.vehicle', 'users.name', 'users.lname')->get();
        
        //Iegūst pašlaik notiekošās rezervācijas un to rezervētājus.
        $reservations = Reservation::where('from', '<=', Carbon::now())
        ->where('until', '>=', Carbon::now())
        ->join('users', 'user', 'users.id')
        ->select('reservations.vehicle', 'users.name', 'users.lname')->get();
        
        //Atzīmē kuri inventāri pašlaik tiek lietoti un kuri uz šo brīdi ir rezervēti.
        foreach ($vehicles as $vehicle) {
            $found = false;
            
            //Lietojums ir svarīgāks par rezervāciju tapēc ja tas tiks atrasts tad $vehicle->usedby lauks tiks pārrakstīts
            foreach ($reservations as $reservation) {
                if ($vehicle->id == $reservation->vehicle) {
                    $usedBy = $reservation->name . '.' . substr($reservation->lname, 0, 1);
                    $vehicle->usedby = $usedBy;
                    $found = true;
                    break; 
                }
            }

            foreach ($vehicleUses as $vehicleUse) {
                if ($vehicle->id == $vehicleUse->vehicle) {
                    $usedBy = $vehicleUse->name . '.' . substr($vehicleUse->lname, 0, 1);
                    $vehicle->usedby = $usedBy;
                    $found = true;
                    break; 
                }
            }

            if (!$found) {
                $vehicle->usedby = "";
            }
        }
        return view("reservationModule.start", compact('vehicles', 'user'));
    }

    //Palīgfunkcija funkcijai RZKL
    public function GetCalendarData(GetCalendar $request)/*GetCalendar*/
    {
        //Vispirms jāpārbauda vai funkcijai tika padoti gada un mēneša parametri,
        //ja nē tad jārāda pašreizējais mēnesis
        $year = $request->input('year');
        $month = $request->input('month');
        $now = Carbon::now();
        //Diena ir svarīga tikai pašreizējajam mēnesim
        if($year == $now->year && $month == $now->month)
        {
            $day = $now->day;
        }
        else{
            $day = -1;
        }
        
        $firstDayOfMonth = Carbon::create($year, $month, 1, 0, 0, 0);
        $monthLength = $firstDayOfMonth->daysInMonth;
        // startOfMonth() atgriež šādas vērtības -> svētdiena = 0 pirmdiena = 1 otrdiena =  2 u.t.t.
        // Šajā kodā tiks izmantota  sistēma  ka pirmdiena = 0 u.t.t.
        $monthStartOn = $firstDayOfMonth->startOfMonth()->dayOfWeek;
        if ($monthStartOn == 0)
        {
            $monthStartOn = 7;
        }
        //Šis skaitlis nepieciešams lai zinātu cik kvadrātiem jābūt kalendārā
        $daysInFirstWeek = (8 - $monthStartOn);
        //dalīšana izmanto peldošā punkta aritmētiku. + 1 ir pirmā nedēļa kuras dienas tika atskaitītas.
        $rowCount = ceil(($monthLength - $daysInFirstWeek) / 7) + 1;
        //Larevel nepiedāvā vienkāršus rīkus kā iegūt latviskos mēnešu nosaukumus tapēc tie tiek cieti kodēti.
        $monthName = "";
        switch ($month) {
            case 1:
                $monthName = "Janvāris";
                break;
            case 2:
                $monthName = "Februāris";
                break;
            case 3:
                $monthName = "Marts";
                break;
            case 4:
                $monthName = "Aprīlis";
                break;
            case 5:
                $monthName = "Maijs";
                break;
            case 6:
                $monthName = "Jūnijs";
                break;
            case 7:
                $monthName = "Jūlijs";
                break;
            case 8:
                $monthName = "Augusts";
                break;
            case 9:
                $monthName = "Septembris";
                break;
            case 10:
                $monthName = "Oktobris";
                break;
            case 11:
                $monthName = "Novembris";
                break;
            case 12:
                $monthName = "Decembris";
                break;
            default:
                break;
        }
        //EndOfMonth metode izmaina oriģinālo objektu.
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        //Iegūst tās rezervācijas kuru beigu laiks ir pēc pirmās mēneša dienas   
        //Un kuru sākuma laiks ir pirms pēdējās mēneša dienas.
        //+ Iegūst dažus laukus no  piesaistītajām tabulām lai varētu parādīt cilvēkam saprotamu tekstu nevis id. 
        $reservations = Reservation::join('vehicles', 'vehicle', '=', 'vehicles.id')
        ->join('users', 'user', '=', 'users.id')
        ->select('reservations.from', 'reservations.until', 'vehicles.name as vehicle', 'vehicles.id as vehicleID', 'users.name as user', 'users.lname as user_lastName')
        ->where('from', '<', $lastDayOfMonth)   
        ->where('until', '>', $firstDayOfMonth)
        ->orderBy('from', 'asc')//Šī kārtošana ir svarīga vēlākai apstrādei.
        ->get();
        
        //Tikko iegūtās rezervācijas sadala pa dienām, katrā  dienā norādot laika intervālu kurā rezervācija ir spēkā.
        $separatedReservations = [];
        foreach ($reservations as $reservation) {
            $reservation->from = Carbon::parse($reservation->from);//No datu bāzes nenāk Carbon datumlaiks.
            $reservation->until = Carbon::parse($reservation->until);
            
            //Ignorē rezervācijas daļu kas atrodas iepriekšējajā mēnesī.
            if($reservation->from < $firstDayOfMonth)
            {
                $startTimeOfReservation = $firstDayOfMonth->copy()->startOfDay();
            }
            else
            {
                $startTimeOfReservation = $reservation->from->copy();
            }

            //Ignorē rezervācijas daļu kas atrodas nākamajā mēnesī.
            if($reservation->until < $lastDayOfMonth)    
            {
                $endTimeOfReservation = $reservation->until;
            }else
            {
                $endTimeOfReservation = $lastDayOfMonth;
            }
            $currentDateTime = $startTimeOfReservation;//Cikla tekošais laiks (mainīgais kas tiek palielināts)
            while ($currentDateTime < $endTimeOfReservation) {
                $todaysReservationEndTime = $currentDateTime->copy()->endOfDay();
                if($reservation->until < $todaysReservationEndTime)
                {
                    //Ja rezervācija beidzas šodien
                    $todaysReservationEndTime = $reservation->until;
                }

                //Iegūst sākuma un beigu laikus kā peldošā punkta skaitli. (šāds formāts izvēlēts laika skalas dēļ)
                //Piemēram 5.3 = 05:18
                $from = ($currentDateTime->hour) + ($currentDateTime->minute / 60);
                $until = ($todaysReservationEndTime->hour) + ($todaysReservationEndTime->minute / 60);

                //Šīs dienas rezervācijām pievieno šo.
                $separatedReservations[$currentDateTime->day][] = [
                    'day' => $currentDateTime->day,
                    'user' => $reservation->user . "." . $reservation->user_lastName[0],
                    'vehicle' => $reservation->vehicle,
                    'vehicleID' => $reservation->vehicleID,
                    'from' => $from,
                    'until' => $until,
                ];
                
                $currentDateTime = $currentDateTime->copy()->startOfDay()->addDay();//Cikla mainīgā palielināšana
            }
        }

        return view("reservationModule.calendar",compact('year', 'month', 'day', 'firstDayOfMonth', 'monthLength', 'monthStartOn', 'rowCount', 'monthName', 'separatedReservations'));    
    }

    //Funkcija AVRZ
    public function ViewMyReservationsPage(Request $request)
    {     
        $myreservations = Reservation::where('user', Auth::user()->id)->orderBy('from', 'desc')
        ->join("vehicles", "vehicle", "vehicles.id")->get();
        return view('reservationModule.myReservations', compact('myreservations'));
    }
    
    //Funkcija kas ļauj izdzēst savas rezervācijas
    public function DeleteMyReservation(SpecificEntry $request)
    {     
        $table = $request->input("table");
        $id = $request->input("id");
        if($table == EntryTypes::RESERVATION->value)
        {   
            $entry = Reservation::find($request->id);
            if($entry->user == Auth::user()->id)
                $entry->delete($entry);
        }
        else{
            AddMessage(Text(110), "k");
        }

        return redirect("manasRezervacijas");
    }

    //Funkcija RZIZ
    public function CreateReservation(MakeReservation $request)
    {     
        $data = $request->validated();
        SharedMethods::CreateReservationLogic($data['vehicle'], $data['from'], $data['until']);
        return redirect("sakums");
    }

    //Funkcija RLIZ
    public function CreateReservationAndUse(MakeReservation $request)
    {     
        $data = $request->validated();
        //Izveido lietojumu lietojums izveidos rezervāciju ja padoti fro  un until
        return SharedMethods::StartVehicleUse($data['vehicle'], $data['until']);
    }
}
