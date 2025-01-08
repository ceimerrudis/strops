<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\VehicleUse;
use App\Models\Vehicle;
use App\Models\Error;

use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Request;
use App\Http\Requests\SpecificEntry;
use App\Http\Requests\NonSpecificEntry;
use App\Http\Requests\TimeCalculationRequest;
use App\Enums\EntryTypes;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;
use App\Services\SharedMethods;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    //Funkcija PVIR.
    public function ViewCreateEntryPage(NonSpecificEntry $request)
    { 
        $table = $request->input('table');
        $entry = new (GetModelFromEnum($table))([]);
        return SharedMethods::EditPage($table, $entry);
    }

    //Funkcija PVIR.
    public function CreateEntry(NonSpecificEntry $request, $redirectToReports = false)
    { 
        $data = $this->ValidateEntry($request, false);
        $table = $request->input('table');
        $model = GetModelFromEnum($table);
        if($table == EntryTypes::REPORT->value)
        {
            //Nedrīkst izveidot divus atskaites ierakstus vienā un tajā pašā mēnesī vienam objektam. 
            $date = Carbon::parse($data['date']);
            $exists = $model::where('object', $data['object'])->whereMonth('date', $date->month)->whereYear('date', $date->year)->exists();
            if($exists)
            {
                addMessage(Text(112), "e");
                return redirect()->back()->withInput();
            }
        }
        $model::create($data);//Izmanto konkrētā modeļa fillable laukus      
        
        if($redirectToReports)
        {
            return redirect()->route('viewReports');
        }else
        {
            return redirect()->route('viewAllEntries', ['table' => $table]);  
        }      
    }

    //Funkcija RDIR.
    public function ViewUpdateEntryPage(SpecificEntry $request)
    { 
        $table = $request->input('table');
        $id = $request->input('id');
        $entry = GetModelFromEnum($table)::findOrFail($id);
        return SharedMethods::EditPage($table, $entry);
    }

    //Funkcija RDIR.
    public function UpdateEntry(SpecificEntry $request, $redirectToReports = false)
    {
        $data = $this->ValidateEntry($request, true);//data satur tikai ieraksta datus
        $table = $request->input('table');
        $model = GetModelFromEnum($table);
        $entry = $model::findOrFail($request->input('id'));
        
        switch((int)$table)
        {
            case EntryTypes::USER->value:
                $entry->username = $data['username'] ?? $entry->username; 
                $entry->password = $data['password'] ?? $entry->password;
                $entry->name = $data['name'] ?? $entry->name; 
                $entry->lname = $data['lname'] ?? $entry->lname;
                $entry->type = $data['type'] ?? $entry->type;
                break;
            case EntryTypes::VEHICLE->value:
                $entry->name = $data['name'] ?? $entry->name;
                $entry->usage = $data['usage'] ?? $entry->usage;
                $entry->usage_type = $data['usage_type'] ?? $entry->usage_type;
                break;
            case EntryTypes::OBJECT->value:
                $entry->code = $data['code'] ?? $entry->code;
                $entry->name = $data['name'] ?? $entry->name;
                $entry->active = $data['active'] ?? $entry->active;
                $entry->user_in_charge = $data['user_in_charge'] ?? $entry->user_in_charge;
                break;
            case EntryTypes::REPORT->value:
                $entry->progress = $data['progress'] ?? $entry->progress;
                if(!empty($data['object']) && $data['object'] != $entry->object){
                    //Nedrīkst rediģēt atskaites objekta lauku. Saglabā ierakstu datu bāzē. 
                    AddMessage(Text(220), "w");
                }
                if(!empty($data['date']) && !CompareMonths($data['date'], $entry->date)){
                    //Nedrīkst rediģēt atskaites datuma lauku. Saglabā ierakstu datu bāzē. 
                    AddMessage(Text(111), "w");
                }
                break;
            case EntryTypes::RESERVATION->value:
                $entry->from = $data['from'] ?? $entry->from;
                $entry->until = $data['until'] ?? $entry->until;
                $entry->user = $data['user'] ?? $entry->user;
                $entry->vehicle = $data['vehicle'] ?? $entry->vehicle;
                break;
            case EntryTypes::VEHICLE_USE->value:
                $entry->from = $data['from'] ?? $entry->from;
                $entry->until = $data['until'] ?? $entry->until;
                $entry->usage_before = $data['usage_before'] ?? $entry->usage_before;
                $entry->usage_after = $data['usage_after'] ?? $entry->usage_after;
                $entry->user = $data['user'] ?? $entry->user;
                $entry->vehicle = $data['vehicle'] ?? $entry->vehicle;
                $entry->object = $data['object'] ?? $entry->object;
                $entry->comment = $data['comment'] ?? $entry->comment;
                break;
            default:
                return redirect()->route('kluda');;
        }

        $entry->save();
        if($redirectToReports)
        {
            return redirect()->route('viewReports');
        }else
        {
            return redirect()->route("viewAllEntries", ['table' => $table]);
        }
    }

    //Funkcija DZIR.
    public function DeleteEntry(SpecificEntry $request)
    { 
        //GetModelFromEnum nevar atgriezt false jo SpecificEntry jau ir to pārbaudījis
        $model = GetModelFromEnum($request->table);
        $entry = $model::find($request->id);
        $entry->delete($entry);
        return redirect()->route("viewAllEntries", ['table' => $request->table]);
    }

    //Funkcija APIR.
    public function ViewAllEntriesPage(NonSpecificEntry $request)
    { 
        $table = $request->table;
        $sortFields = [
            EntryTypes::USER->value => ['name', 'asc'],
            EntryTypes::VEHICLE->value => ['name', 'asc'],
            EntryTypes::OBJECT->value => ['name', 'asc'],
            EntryTypes::REPORT->value => ['date', 'desc'],
            EntryTypes::RESERVATION->value => ['from', 'desc'],
            EntryTypes::VEHICLE_USE->value => ['from', 'desc'],
            EntryTypes::ERROR->value => ['time', 'desc'],
        ];
        $sortFieldName = $sortFields[$table];

        $allHeaders = [//Tabulas galvene
            EntryTypes::USER->value => ["Lietotājvārds", "Vārds", "Uzvārds", "Loma"],
            EntryTypes::VEHICLE->value => ["Inventāra nosaukums", "Nolietojums", "Lietojuma veids", "Id"],
            EntryTypes::OBJECT->value => ["Objekta Nr.", "Nosaukums", "Aktīvs / Slēgts"],
            EntryTypes::REPORT->value => ["Objekts", "Progress", "Datums"],
            EntryTypes::RESERVATION->value => ["Lietotājs", "Inventārs", "Datums/laiks no","Datums/laiks līdz"],
            EntryTypes::VEHICLE_USE->value => ["Lietotājs", "Inventārs", "Objekts", "Lietojums sākot", "Lietojums beidzot", "Datums/laiks no", "Datums/laiks līdz", "Komentārs"],
            EntryTypes::ERROR->value => ["Komentārs", "Laiks", "Rezervācija", "Lietojums", "Nolietojums pirms", "Nolietojums pēc"],
        ];

        $tableName = [//Akuzatīvs
            EntryTypes::USER->value => "lietotāju",
            EntryTypes::VEHICLE->value => "inventāru",
            EntryTypes::OBJECT->value => "objektu",
            EntryTypes::REPORT->value => "atskaiti",
            EntryTypes::RESERVATION->value => "rezervāciju",
            EntryTypes::VEHICLE_USE->value => "lietojumu",
            EntryTypes::ERROR->value => "kļūdu",
        ];

        $joinableTables = [
            EntryTypes::USER->value => [],
            EntryTypes::VEHICLE->value => [],
            EntryTypes::OBJECT->value => [],
            EntryTypes::REPORT->value => [["table" => "objects", "columns" => ["code"]]],
            EntryTypes::RESERVATION->value => [["table" => "users", "columns" => ["username"]], ["table" => "vehicles", "columns" => ["name"]]],
            EntryTypes::VEHICLE_USE->value => [["table" => "users", "columns" => ["username"]], ["table" => "vehicles", "columns" => ["name", "usage_type"]], ["table" => "objects", "columns" => ["code"]]],
            EntryTypes::ERROR->value => [],
        ];

        $tablesToJoin = $joinableTables[$table];
        $viewName = EntryTypes::GetViewName($table);
        $headers = $allHeaders[$table];
        $name = $tableName[$table];
        //Iegūst visus konkrētā veida ierakstus, sakārto tos vai nu alfabēta secībā pēc nosaukuma, vai arī pēc lauka sākuma laiks.
        $query = GetModelFromEnum($table)::orderBy($sortFieldName[0], $sortFieldName[1])->select(EntryTypes::GetName($table).'.*');
        
        foreach ($tablesToJoin as $joinedTable) {
            $query->join($joinedTable['table'], substr($joinedTable['table'], 0, -1), $joinedTable['table'].'.id');//Sagadīšanās pēc var izmantot tabulas nosaukumu bez pēdējā burta
            foreach ($joinedTable['columns'] as $column) {
                $query->addSelect($joinedTable['table'] . '.' . $column . ' as ' . $joinedTable['table'] . '_' . $column);
            }
        }

        $allEntryData = $query->get();

        //Atskaites tiek sagrupētas pa objektiem un sakārtotas pēc datumiem. TODO


        return view('adminModule.allEntries', compact('table', 'allEntryData', 'name', 'headers', 'viewName'));
    }

    //funkcija RKLT
    public function RecalculateTime(TimeCalculationRequest $request)
    { 
        $timeInMinutes = SharedMethods::RecalculateTimeCalculation($request->input("from"), $request->input("until"));
        $timeInDays = $timeInMinutes / 60 /24;
        $data = [
            'time' => $timeInDays,
        ];
        return response()->json($data);
    }

    //Šī palīgfunkcija pārbauda lietotāju datu atbilstību nepieciešamajam formātam.
    public function ValidateEntry(Request $request, bool $update)
    {
        //Vispirms nodefinē noteikumus tad paziņojumus par kļūdām, 
        //tad, ja padota pareiza tabulas vērtība, veic pārbaudi.
        $passwordRequired = 'required';
        $reportFieldsRequired = 'required';
        if($update){
            $reportFieldsRequired = 'nullable';
            $passwordRequired = 'nullable';
        }
        //Web formu standartu veidotāji uzskata ka false === null. Šī iemesla dēļ tagad jāčakarējas
        //Laravel veidotāji neprot pārbaudīt bool vērtības tapēc tas jādara manuāli
        $request->merge(['active' => (bool)$request->has('active')]);

        $validationRules = [
            EntryTypes::USER->value => [
                'username' => 
                    ['required',
                    'string',
                    'regex:/^[\p{L}\p{M}\p{N}_-]+$/u',
                    Rule::unique('users', 'username')->ignore($request->id)],//id var būt null. Šajā gadījumā tiks skatīti visi lietotājvārdi
                'password' => 'string|'.$passwordRequired,//Parole nepieciešama tikai izveidojot. un tā kā paroli nevar sūtīt lietotājam lauks būs tukšs
                'name' => 'required|string',
                'lname' => 'required|string',
                'type' => [ 
                    'required',
                    'integer',
                    new Enum(UserTypes::class),//Pārbauda vai padotā vērtība atrodama enumeratorā.
                ],
            ],
            EntryTypes::OBJECT->value => [
                'code' => 
                    ['required',
                    'min:1',
                    'max:10',
                    Rule::unique('objects', 'code')->ignore($request->id)],//id var būt null. Šajā gadījumā tiks skatīti visi kodi
                'name' => 'required|string',
                'active' => 'nullable',
                'user_in_charge' => 'nullable|exists:users,id',
            ],
            EntryTypes::VEHICLE->value => [
                'name' => 'required|string',
                'usage' => 'required|numeric|min:0',
                'usage_type' => [
                    'required',
                    'numeric',
                    'integer', 
                    new Enum(VehicleUsageTypes::class),//Pārbauda vai padotā vērtība atrodama enumeratorā.
                ],
            ],
            EntryTypes::RESERVATION->value => [
                'from' => 'required|date',
                'until' => 'required|date',
                'user' => 'required|numeric|exists:users,id',
                'vehicle' => 'required|numeric|exists:vehicles,id',
            ],
            EntryTypes::REPORT->value => [
                'progress' => 'required|numeric|min:0|max:100',
                'object' => $reportFieldsRequired.'|exists:objects,id',
                'date' => $reportFieldsRequired.'|date',
            ],
            EntryTypes::VEHICLE_USE->value => [
                'from' => 'required|date',
                'until' => 'nullable|date',
                'usage_before' => 'required|numeric|min:0',
                'usage_after' => 'nullable|numeric|min:0',
                'user' => 'required|exists:users,id',
                'vehicle' => 'required|exists:vehicles,id',
                'object' => 'required|exists:objects,id',
                'comment' => 'string|nullable',
            ],
        ];

        $messages = [
            EntryTypes::USER->value => [
                'username.required' => Text(156),
                'username.unique' => Text(157),
                'username.string' => Text(158),
                'username.regex' => Text(221),
                'password.string' => Text(159),
                'name.required' => Text(160),
                'name.string' => Text(161),
                'lname.required' => Text(162),
                'lname.string' => Text(163),
                'type.required' => Text(164),
                'type.integer' => Text(165),
                'type.in' => Text(166),
            ],
            EntryTypes::OBJECT->value => [
                'code.required' => Text(167),
                'code.min' => Text(168),
                'code.max' => Text(169),
                'code.unique' => Text(170),
                'name.required' => Text(171),
                'name.string' => Text(172),
                'user_in_charge.exists' => Text(175),
            ],
            EntryTypes::VEHICLE->value => [
                'name.required' => Text(176),
                'name.string' => Text(177),
                'usage.required' => Text(178),
                'usage.numeric' => Text(179),
                'usage.min' => Text(180),
                'usage_type.required' => Text(181),
                'usage_type.numeric' => Text(182),
                'usage_type.integer' => Text(183),
                'usage_type.in' => Text(184),
            ],
            EntryTypes::RESERVATION->value => [
                'from.required' => Text(185),
                'from.date' => Text(186),
                'until.required' => Text(187),
                'until.date' => Text(188),
                'user.required' => Text(189),
                'user.numeric' => Text(190),
                'user.exists' => Text(191),
                'vehicle.required' => Text(192),
                'vehicle.numeric' => Text(193),
                'vehicle.exists' => Text(194),
            ],
            EntryTypes::REPORT->value => [
                'progress.required' => Text(195),
                'progress.numeric' => Text(196),
                'progress.min' => Text(197),
                'date.required' => Text(198),
                'date.date' => Text(199),
                'object.required' => Text(200),
                'object.exists' => Text(201),
            ],
            EntryTypes::VEHICLE_USE->value => [
                'from.required' => Text(202),
                'from.date' => Text(203),
                'until.nullable' => Text(204),
                'until.date' => Text(205),
                'usage_before.required' => Text(206),
                'usage_before.numeric' => Text(207),
                'usage_before.min' => Text(208),
                'usage_after.nullable' => Text(209),
                'usage_after.numeric' => Text(210),
                'usage_after.min' => Text(211),
                'user.required' => Text(212),
                'user.exists' => Text(213),
                'vehicle.required' => Text(214),
                'vehicle.exists' => Text(215),
                'object.required' => Text(216),
                'object.exists' => Text(217),
                'comment.string' => Text(218),
            ],
        ];

        $table = $request->input('table');

        if (!array_key_exists($table, $validationRules)) {
            return response()->json(['error' => ''], 400);
        }

        return $request->validate($validationRules[$table], $messages[$table]);
    }
}
