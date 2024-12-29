<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectModel;
use App\Models\Report;
use App\Requests\CreateReport;
use App\Requests\ViewCreateReport;
use App\Requests\ViewReport;

class ObjectController extends Controller
{    
    //Funkcija ATJO
    public function UpdateObjects(Request $request)
    { 
        $code = Artisan::call('app:fetch-excel-object-data');
        $output = Artisan::output();
        $str = Text(110);
        if($code === 0)
            $str = Text(109);
        return response()->json(['message' => $str, 'output' => $output]);
    }

    //Funkcija PVAT
    public function ViewCreateReportPage(ViewCreateReport $request)
    { 
        $object = $request->input("object");
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        return view("objectModule.createReport", compact('object', 'year', 'month'));
    }

    //Funkcija PVAT
    public function CreateReport(CreateReport $request)
    { 
        $data = $request->validated();
        $year = $data['year'];
        $month = $data['month'];
        $object = $data['object'];

        //Ja šim objektam šajā mēnesī nav atskaites izveido atskaiti un aizpilda ar novērtējumu.
        $reportExists = Report::where('object', $object)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->exists();
        if($reportExists)
        {
            AddMessage(Text(147), "w");
            return back();       
        }else
        {
            //Pārbauda vai lietotājs ir objekta atbildīgais.
            if(ObjectModel::where("id", $object)->where("user_in_charge", Auth::user()->id)->exists())
            {
                //Izveido atskaiti.
                Report::create($data);
            }
            else
            {
                AddMessage(Text(151), "k");
            }
        }
        return redirect("ViewReports");//TODO
    }

    //Funkcija RDAT
    public function ViewUpdateReportPage(ViewReport $request)
    { 
        $objectId = $request->input("object");
        $reportId = $request->input("report");
        if(!ObjectModel::where("id", $objectId)->where("user_in_charge", Auth::user()->id)->exists())
        {
            return redirect("ViewReports");//TODO
        }

        //Nedrīkst rediģēt atskaites datuma lauku.
        $report = Report::where("id", $reportId)->first();
        return view("objectModule.UpdateReport", compact('report'));
    }

    //Funkcija RDAT
    public function UpdateReport(CreateReport $request)
    { 
        //Nedrīkst rediģēt atskaites datuma lauku.
    }

    //Funkcija SVAT
    public function ViewReports(Request $request)
    { 
        $objects = ObjectModel::where('user_in_charge', Auth::user()->id)->get();
        $reports = Report::whereIn('object', $objects->pluck('id'))->get();
        $reportsByObject = $reports->groupBy('object');
        return view('your-view-name', compact('objects', 'reportsByObject'));
    }
}
