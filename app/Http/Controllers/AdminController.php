<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SpecificEntry;
use App\Http\Requests\NonSpecificEntry;
use App\Enums\EntryTypes;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;

class AdminController extends Controller
{
    //Funkcija PVIR.
    public function ViewCreateEntryPage(NonSpecificEntry $request)
    { 
        return view("adminModule.ViewCreateEntryPage", compact($request->table));
    }

    //Funkcija PVIR.
    public function CreateEntry(NonSpecificEntry $request)
    { 
        //Nedrīkst izveidot divus atskaites ierakstus vienā un tajā pašā mēnesī vienam objektam. Izveido jaunu ierakstu ar dotajiem datiem.
        ValidateEntry($request);
        if($request->table === EntryTypes::Report)
        {
            //addmessage didnt create shit
        }
        GetModelFromEnum($request->table)::create();
    }

    //Funkcija RDIR.
    public function ViewUpdateEntryPage(NonSpecificEntry $request)
    { 
        return view("adminModule.ViewCreateEntryPage", compact($request->table));
    }

    //Funkcija RDIR.
    public function UpdateEntry(SpecificEntry $request)
    {
        ValidateEntry($request);
        if(true){
            //add message didnt edit date field
        }
        //Nedrīkst rediģēt atskaites datuma lauku. Saglabā ierakstu datu bāzē. 
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
        $data = GetModelFromEnum($request->table)::get();
        //Iegūst visus konkrētā veida ierakstus, sakārto tos vai nu alfabēta secībā pēc nosaukuma, vai arī pēc lauka sākuma laiks.
        //Atskaites tiek sagrupētas pa objektiem un sakārtotas pēc datumiem.
        return view('TODO', compact($data));
    }

    //funkcija RKLT
    public function RecalculateTime(Request $request)
    { 
        //Rēķinot laiku tiek summēti visi laika intervāli starp sākuma un beigu laiku kas atrodas arī laika intervālā (07:00 – 18:00).
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
                'progress' => 'requred|numeric|min:0',
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
