<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectModel;
use App\Models\Report;

class ObjectController extends Controller
{    
    //Funkcija ATJO
    public function UpdateObjects(Request $request)
    { 
        $code = Artisan::call('app:fetch-excel-object-data');
        $output = Artisan::output();
        $str = text(110);
        if($code === 0)
            $str = text(109);
        return response()->json(['message' => $str, 'output' => $output]);
    }

    //Funkcija PVAT
    public function ViewCreateReportPage(Request $request)
    { 
        //Ja šim objektam šajā  mēnesī nav atskaites izveido atskaiti un aizpilda ar novērtējumu.
    }

    //Funkcija PVAT
    public function CreateReport( $request)
    { 
        //Ja šim objektam šajā  mēnesī nav atskaites izveido atskaiti un aizpilda ar novērtējumu.
    }

    //Funkcija RDAT
    public function ViewUpdateReportPage(Request $request)
    { 
        //Nedrīkst rediģēt atskaites datuma lauku.
    }

    //Funkcija RDAT
    public function UpdateReport( $request)
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
