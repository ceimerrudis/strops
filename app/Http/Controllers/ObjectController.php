<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Http\Requests\CreateReport;
use App\Http\Requests\ViewCreateReport;
use App\Http\Requests\ViewReport;
use Illuminate\Support\Facades\Auth;
use App\Enums\EntryTypes;
use App\Services\SharedMethods;
use App\Http\Requests\NonSpecificEntry;
use App\Http\Requests\SpecificEntry;
use Illuminate\Support\Facades\Artisan;

class ObjectController extends Controller
{   
    //Funkcija ATJO
    public function UpdateObjects(Request $request)
    { 
        $code = Artisan::call('app:synchronize-object-data');
        $output = Artisan::output();
        $str = Text(110);
        if($code === 0)
            $str = Text(109);
        return response()->json(['message' => $str, 'output' => $output]);
    } 

    //PVAT un RDAT
    public function GetReportEditPage($entry, $CreatingNew)
    {
        $viewName = 'report';
        $justReport = true;
        $table = EntryTypes::REPORT->value;
        $createRouteName = 'addReport';
        $editRouteName = 'editReport';
        return view("adminModule.createEntry", compact('createRouteName', 'editRouteName', 'table', 'viewName', 'entry', 'justReport', 'CreatingNew'));
    }

    //PVAT un RDAT
    public function GetRules()
    {
        return $rules =[
            'object' => 'required|exists:objects,id',
        ];
    }

    //PVAT un RDAT
    public function GetMessages()
    {
        return $mesages = [
            'object.required' => Text(145),
            'object.exists' => Text(146),
        ];
    }

    //PVAT un RDAT
    public function AllowedToEdit(Request $request){
        $objId = $request->validate($this::GetRules(), $this::GetMessages())['object'];
        
        $request->merge([
            'table' => EntryTypes::REPORT->value,
        ]);
        $object = ObjectModel::findOrFail($objId);
        return $object->user_in_charge == Auth::user()->id;
    }

    //Funkcija PVAT
    public function ViewCreateReportPage(ViewCreateReport $request)
    { 
        $objId = $request->input('object');
        $object = ObjectModel::findOrFail($objId);
        $entry = new Report(['object' => $objId]);
        $entry->code = $object->code;
        if($object->user_in_charge == Auth::user()->id){
            return $this::GetReportEditPage($entry, true);
        }
        return redirect()->back();
    }

    //Funkcija PVAT
    public function CreateReport(Request $request)
    { 
        if($this::AllowedToEdit($request)){
            $NonSpecific = NonSpecificEntry::createFrom($request);
            return (new AdminController)->CreateEntry($NonSpecific, true);
        }
        return redirect("apskatitatskaites");
    }

    //Funkcija RDAT
    public function ViewUpdateReportPage(ViewReport $request)
    {
        $reportId = $request->input("id");
        $entry = Report::findOrFail($reportId);
        $object = ObjectModel::findOrFail($entry->object);
        $entry->code = $object->code;
        if($object->user_in_charge == Auth::user()->id){
            return $this::GetReportEditPage($entry, false);
        }
        return redirect()->back();
    }

    //Funkcija RDAT
    public function UpdateReport(Request $request)
    { 
        if($this::AllowedToEdit($request)){
            $Specific = SpecificEntry::createFrom($request);
            return (new AdminController)->UpdateEntry($Specific, true);
        }
        return redirect("apskatitatskaites");
    }

    //Funkcija SVAT
    public function ViewReports(Request $request)
    {
        $objects = ObjectModel::where('user_in_charge', Auth::user()->id)->get();
        $reports = Report::whereIn('object', $objects->pluck('id'))->get();
        
        //Izveido vienkāršu datu struktūru
        $reportsByObject = []; 
        foreach($objects as $object)
        {
            $thisObjReports = [];
            foreach($reports as $report)
            {
                if($report->object == $object->id)
                {
                    $thisObjReports[] = ['id' => $report->id, 'progress' => $report->progress, 'date' => $report->date];
                }
            }
            $reportsByObject[$object->id] = ['reports' => $thisObjReports, 'id' => $object->id, 'code' => $object->code, 'name' =>$object->name ];
        }

        return view('objectModule.reports', compact('reportsByObject'));
    }
}
