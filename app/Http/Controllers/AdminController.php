<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\VehicleUse;
use App\Models\Vehicle;
use App\Models\Error;

use Illuminate\Http\Request;
use App\Http\Requests\SpecificEntry;
use App\Http\Requests\NonSpecificEntry;
use App\Http\Requests\TimeCalculationRequest;
use App\Enums\EntryTypes;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;

class AdminController extends Controller
{
    //Funkcija PVIR.
    public function ViewCreateEntryPage(NonSpecificEntry $request)
    { 
        $table = $request->input('table');
        return view("adminModule.ViewCreateEntryPage", compact('table'));
    }

    //Funkcija PVIR.
    public function CreateEntry(NonSpecificEntry $request)
    { 
        ValidateEntry($request);
        $data = $request->all();
        $model = GetModelFromEnum($data['table']);
        if($data['table'] === EntryTypes::Report)
        {
            //Nedrīkst izveidot divus atskaites ierakstus vienā un tajā pašā mēnesī vienam objektam. 
            $date = $data['date'];
            $exists = $model::whereDate('date', $date)->exists();
            if($exists)
            {
                addMessage(Text(112), "e");
                return redirect()->back();//Atgriežamies izveidošanas lapā
            }
        }

        $model::create($data);//Izmanto konkrētā modeļa fillable laukus      
        return redirect()->route('viewAllEntries', ['table' => $table]);        
    }

    //Funkcija RDIR.
    public function ViewUpdateEntryPage(SpecificEntry $request)
    { 
        $id = $request->input('id');
        $table = $request->input('table');
        $entry = GetModelFromEnum($table)::findOrFail($id);
        return view("adminModule.ViewCreateEntryPage", compact('entry', '$request->table'));
    }

    //Funkcija RDIR.
    public function UpdateEntry(SpecificEntry $request)
    {
        $data = $request->all();
        ValidateEntry($request);
        $table = $request->$table;
        $model = GetModelFromEnum($table);

        $entry = $model::findOrFail($data['id']);
     
        switch($table)
        {
            case EntryTypes::USER:
                $entry->username = $data['username'] ?? $entry->username; 
                $entry->password = $data['password'] ?? $entry->password;
                $entry->name = $data['name'] ?? $entry->name; 
                $entry->lname = $data['lname'] ?? $entry->lname;
                $entry->type = $data['type'] ?? $entry->type;
                break;
            case EntryTypes::VEHICLE:
                $entry->name = $data['name'] ?? $entry->name;
                $entry->usage = $data['usage'] ?? $entry->usage;
                $entry->usagetype = $data['usagetype'] ?? $entry->usagetype;
                break;
            case EntryTypes::OBJECT:
                $entry->code = $data['code'] ?? $entry->code;
                $entry->name = $data['name'] ?? $entry->name;
                $entry->closed = $data['closed'] ?? $entry->closed;
                $entry->userInCharge = $data['userInCharge'] ?? $entry->userInCharge;
                break;
            case EntryTypes::REPORT:
                $entry->progress = $data['progress'] ?? $entry->progress;
                $entry->object = $data['object'] ?? $entry->object;
                if(!empty($data['date'])){
                    //Nedrīkst rediģēt atskaites datuma lauku. Saglabā ierakstu datu bāzē. 
                    AddMessage(Text(111), "w");
                }
                break;
            case EntryTypes::RESERVATION:
                $entry->from = $data['from'] ?? $entry->from;
                $entry->until = $data['until'] ?? $entry->until;
                $entry->user = $data['user'] ?? $entry->user;
                $entry->vehicle = $data['vehicle'] ?? $entry->vehicle;
                break;
            case EntryTypes::VEHICLE_USE:
                $entry->from = $data['from'] ?? $entry->from;
                $entry->until = $data['until'] ?? $entry->until;
                $entry->usageBefore = $data['usageBefore'] ?? $entry->usageBefore;
                $entry->usageAfter = $data['usageAfter'] ?? $entry->usageAfter;
                $entry->user = $data['user'] ?? $entry->user;
                $entry->vehicle = $data['vehicle'] ?? $entry->vehicle;
                $entry->object = $data['object'] ?? $entry->object;
                $entry->comment = $data['comment'] ?? $entry->comment;
                break;
            default:
                return redirect()->route('kluda');;
        }

        $entry->save();
        return redirect()->route("viewAllEntries")->with('table', $table);
    }

    //Funkcija DZIR.
    public function DeleteEntry(SpecificEntry $request)
    { 
        //GetModelFromEnum nevar atgriezt false jo EditEntry jau ir to pārbaudījis
        GetModelFromEnum($request->table)::deleteAt($request->id);
    }

    //Funkcija APIR.
    public function ViewAllEntriesPage(NonSpecificEntry $request)
    { 
        $sortFields = [
            EntryTypes::USER => ['name', 'asc'],
            EntryTypes::VEHICLE => ['name', 'asc'],
            EntryTypes::OBJECT => ['name', 'asc'],
            EntryTypes::REPORT => ['date', 'desc'],
            EntryTypes::RESERVATION => ['from', 'desc'],
            EntryTypes::VEHICLE_USE => ['from', 'desc'],
            EntryTypes::ERROR => ['time', 'desc'],
        ];
        $sortFieldName = $sortFields[$request->table];

        $allEntryData = GetModelFromEnum($request->table)::orderBy($sortFieldName[0], $sortFieldName[1])->get();
        //Iegūst visus konkrētā veida ierakstus, sakārto tos vai nu alfabēta secībā pēc nosaukuma, vai arī pēc lauka sākuma laiks.
        //Atskaites tiek sagrupētas pa objektiem un sakārtotas pēc datumiem.
        return view('viewAllEntries', compact('allEntryData'));
    }

    //funkcija RKLT
    public function RecalculateTime(TimeCalculationRequest $request)
    { 
        $data = [
            'time' => RecalculateTimeCalculation($request->$from, $request->$until),//TODO format in days
        ];
        return response()->json($data);
    }
    
    //funkcija RKLT
    public function RecalculateTimeCalculation($from, $until) : int
    { 
        $WORK_DAY_BEGINS = 7;//CONFIG
        $WORK_DAY_BEGINS_STR = "07:00";
        $WORK_DAY_ENDS = 18;
        $WORK_DAY_ENDS_STR = "18:00";

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

    //Šī palīgfunkcija pārbauda lietotāju datu atbilstību nepieciešamajam formātam.
    public function ValidateEntry(Request $request)
    {
        //Vispirms nodefinē noteikumus tad paziņojumus par kļūdām, 
        //tad, ja padota pareiza tabulas vērtība, veic pārbaudi.

        $validationRules = [
            EntryTypes::USER => [
                'username' => 'required|unique:users,username|string',
                'password' => 'string',
                'name' => 'required|string',
                'lname' => 'required|string',
                'type' => [ 
                    'required|integer',
                    Rule::in(array_column(UserTypes::cases(), 'value')),//Pārbauda vai padotā vērtība atrodama sarakstā.
                ],
            ],
            EntryTypes::OBJECT => [
                'code' => 'required|min:1|max:10|unique:objects,code',
                'name' => 'required|string',
                'closed' => 'required|boolean',
                'userInCharge' => 'exists:users,id',
            ],
            EntryTypes::VEHICLE => [
                'name' => 'required|string',
                'usage' => 'required|numeric|min:0',
                'usagetype' => [
                    'required|numeric|integer', 
                    Rule::in(array_column(VehicleUsageTypes::cases(), 'value')),//Pārbauda vai padotā vērtība atrodama sarakstā.
                ],
            ],
            EntryTypes::RESERVATION => [
                'from' => 'required|date',
                'until' => 'required|date',
                'user' => 'required|numeric|exists:users,id',
                'vehicle' => 'required|numeric|exists:vehicles,id',
            ],
            EntryTypes::REPORT => [
                'progress' => 'required|numeric|min:0',
                'object' => 'required|exists:objects,id',
                'date' => 'required|date',
            ],
            EntryTypes::VEHICLE_USE => [
                'from' => 'required|date',
                'until' => 'nullable|date',
                'usageBefore' => 'required|numeric|min:0',
                'usageAfter' => 'nullable|numeric|min:0',
                'user' => 'required|exists:users,id',
                'vehicle' => 'required|exists:vehicles,id',
                'object' => 'required|exists:objects,id',
                'comment' => 'string|nullable',
            ],
        ];

        //TODO export text
        $messages = [
            EntryTypes::USER => [
                'username.required' => 'Lūdzu ievadiet lietotājvārdu.',
                'username.unique' => 'Lietotājvārds jau eksistē.',
                'username.string' => 'Lietotājvārds jābūt teksta vērtībai.',
                'password.string' => 'Parole jābūt teksta vērtībai.',
                'name.required' => 'Lūdzu ievadiet vārdu.',
                'name.string' => 'Vārds jābūt teksta vērtībai.',
                'lname.required' => 'Lūdzu ievadiet uzvārdu.',
                'lname.string' => 'Uzvārds jābūt teksta vērtībai.',
                'type.required' => 'Lūdzu izvēlieties lietotāja veidu.',
                'type.integer' => 'Lietotāja veids jābūt vesels skaitlis.',
                'type.in' => 'Izvēlētais lietotāja veids nav derīgs.',
            ],
            EntryTypes::OBJECT => [
                'code.required' => 'Lūdzu ievadiet kodu.',
                'code.min' => 'Koda garumam jābūt vismaz 1 rakstzīmei.',
                'code.max' => 'Koda garumam nedrīkst pārsniegt 10 rakstzīmes.',
                'code.unique' => 'Šāds kods jau eksistē.',
                'name.required' => 'Lūdzu ievadiet nosaukumu.',
                'name.string' => 'Nosaukumam jābūt teksta vērtībai.',
                'closed.required' => 'Lūdzu norādiet, vai objekts ir slēgts.',
                'closed.boolean' => 'Statusam jābūt true vai false.',
                'userInCharge.exists' => 'Norādītais lietotājs nav atrasts.',
            ],
            EntryTypes::VEHICLE => [
                'name.required' => 'Lūdzu ievadiet nosaukumu.',
                'name.string' => 'Nosaukumam jābūt teksta vērtībai.',
                'usage.required' => 'Lūdzu ievadiet lietojuma daudzumu.',
                'usage.numeric' => 'Lietojumam jābūt skaitliskai vērtībai.',
                'usage.min' => 'Lietojumam jābūt vismaz 0.',
                'usagetype.required' => 'Lūdzu izvēlieties lietošanas veidu.',
                'usagetype.numeric' => 'Lietošanas veidam jābūt skaitliskai vērtībai.',
                'usagetype.integer' => 'Lietošanas veidam jābūt veselam skaitlim.',
                'usagetype.in' => 'Izvēlētais lietošanas veids nav derīgs.',
            ],
            EntryTypes::RESERVATION => [
                'from.required' => 'Lūdzu izvēlieties sākuma datumu.',
                'from.date' => 'Sākuma datumam jābūt derīgam datuma formātā.',
                'until.required' => 'Lūdzu izvēlieties beigu datumu.',
                'until.date' => 'Beigu datumam jābūt derīgam datuma formātā.',
                'user.required' => 'Lūdzu izvēlieties lietotāju.',
                'user.numeric' => 'Lietotājam jābūt skaitliskai vērtībai.',
                'user.exists' => 'Norādītais lietotājs nav atrasts.',
                'vehicle.required' => 'Lūdzu izvēlieties transportlīdzekli.',
                'vehicle.numeric' => 'Transportlīdzeklim jābūt skaitliskai vērtībai.',
                'vehicle.exists' => 'Norādītais transportlīdzeklis nav atrasts.',
            ],
            EntryTypes::REPORT => [
                'progress.required' => 'Lūdzu ievadiet progresu.',
                'progress.numeric' => 'Progresam jābūt skaitliskai vērtībai.',
                'progress.min' => 'Progresam jābūt vismaz 0.',
                'date.required' => 'Lūdzu ievadiet datumu.',
                'date.date' => 'Datumam jābūt derīgam datuma formātā.',
                'object.required' => 'Objektam jābūt norādītam.',
                'object.exists' => 'Objekts netika atrasts datu bāzē.',
            ],
            EntryTypes::VEHICLE_USE => [
                'from.required' => 'Lūdzu izvēlieties sākuma datumu.',
                'from.date' => 'Sākuma datumam jābūt derīgam datuma formātā.',
                'until.nullable' => 'Beigu datums ir izvēles lauks.',
                'until.date' => 'Beigu datumam jābūt derīgam datuma formātā.',
                'usageBefore.required' => 'Lūdzu ievadiet lietojuma daudzumu pirms.',
                'usageBefore.numeric' => 'Lietojuma daudzumam jābūt skaitliskai vērtībai.',
                'usageBefore.min' => 'Lietojuma daudzumam jābūt vismaz 0.',
                'usageAfter.nullable' => 'Lūdzu ievadiet lietojuma daudzumu pēc.',
                'usageAfter.numeric' => 'Lietojuma daudzumam jābūt skaitliskai vērtībai.',
                'usageAfter.min' => 'Lietojuma daudzumam jābūt vismaz 0.',
                'user.required' => 'Lūdzu izvēlieties lietotāju.',
                'user.exists' => 'Norādītais lietotājs nav atrasts.',
                'vehicle.required' => 'Lūdzu izvēlieties transportlīdzekli.',
                'vehicle.exists' => 'Norādītais transportlīdzeklis nav atrasts.',
                'object.required' => 'Lūdzu izvēlieties objektu.',
                'object.exists' => 'Norādītais objekts nav atrasts.',
                'comment.string' => 'Komentāram jābūt teksta vērtībai.',
            ],
        ];
    
        $table = $request->input('table');
    
        if (!array_key_exists($table, $validationRules)) {
            return response()->json(['error' => ''], 400);
        }
    
        return $request->validate($validationRules[$table], $messages[$table]);
    }
}
