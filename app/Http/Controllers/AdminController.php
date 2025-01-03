<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\VehicleUse;
use App\Models\Vehicle;
use App\Models\Error;

use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use App\Http\Requests\SpecificEntry;
use App\Http\Requests\NonSpecificEntry;
use App\Http\Requests\TimeCalculationRequest;
use App\Enums\EntryTypes;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;
use App\Services\SharedMethods;

class AdminController extends Controller
{
    //Funkcija PVIR.
    public function ViewCreateEntryPage(NonSpecificEntry $request)
    { 
        $table = $request->input('table');
        $viewName = EntryTypes::GetName($table);
        $entry = new (GetModelFromEnum($table))([]);//Izveido tukšu elementu 
        return view("adminModule.createEntry", compact('table', 'viewName', 'entry'));
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
        return view("adminModule.createEntry", compact('entry', '$request->table'));
    }

    //Funkcija RDIR.
    public function UpdateEntry(SpecificEntry $request)
    {
        $data = $request->validated();
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
        //GetModelFromEnum nevar atgriezt false jo SpecificEntry jau ir to pārbaudījis
        GetModelFromEnum($request->table)::deleteAt($request->id);
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
            EntryTypes::VEHICLE_USE->value => ["Lietotājs", "Inventārs", "Objekts, Lietojums", "Lietojums sākot / beidzot", "Datums/laiks no", "Datums/laiks līdz", "Komentārs"],
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
        $viewName = EntryTypes::GetName($table);
        $headers = $allHeaders[$table];
        $name = $tableName[$table];
        $allEntryData = GetModelFromEnum($table)::orderBy($sortFieldName[0], $sortFieldName[1])->get();
        //Iegūst visus konkrētā veida ierakstus, sakārto tos vai nu alfabēta secībā pēc nosaukuma, vai arī pēc lauka sākuma laiks.
        //Atskaites tiek sagrupētas pa objektiem un sakārtotas pēc datumiem.
        return view('adminModule.allEntries', compact('table', 'allEntryData', 'name', 'headers', 'viewName'));
    }

    //funkcija RKLT
    public function RecalculateTime(TimeCalculationRequest $request)
    { 
        $data = [
            'time' => SharedMethods::RecalculateTimeCalculation($request->$from, $request->$until),//TODO format in days
        ];
        return response()->json($data);
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
                    'required',
                    'integer',
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
                    'required',
                    'numeric',
                    'integer', 
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
