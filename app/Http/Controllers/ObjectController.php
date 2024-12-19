<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObjectController extends Controller
{    
    //Funkcija ATJO
    public function UpdateObjects(Request $request)
    { 
        Iegūst visus objektus no datu bāzes. Iet cauri failam, ja atrod objektu kas netiek atrasts datu bāzē tad pievieno to. Ja tiek atrasts objekts kam statuss = “slēgts”, tad pārbauda vai datubāzē šis objekts arī atzīmēts kā slēgts. Ja tā nav tad šo objektu datu bāzē atzīmē kā slēgtu.
    }

    //Funkcija PVAT
    public function ViewCreateReportPage(Request $request)
    { 
        Ja šim objektam šajā  mēnesī nav atskaites izveido atskaiti un aizpilda ar novērtējumu.
    }

    //Funkcija PVAT
    public function CreateReport( $request)
    { 
        Ja šim objektam šajā  mēnesī nav atskaites izveido atskaiti un aizpilda ar novērtējumu.
    }

    //Funkcija RDAT
    public function ViewUpdateReportPage(Request $request)
    { 
        Nedrīkst rediģēt atskaites datuma lauku.
Datu bāzē tiek ierakstīta jaunā vērtība.
    }

    //Funkcija RDAT
    public function UpdateReport( $request)
    { 
        Nedrīkst rediģēt atskaites datuma lauku.
Datu bāzē tiek ierakstīta jaunā vērtība.
    }

    //Funkcija SVAT
    public function ViewReports(Request $request)
    { 
        Atgriež sarakstu ar šī lietotāja objektiem un objektu atskaitēm.
    }
}
