<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    //Funkcijas RZKL atver kalendāra lapu
    public function ViewCalendarPage(Request $request)
    {  
        Iegūst visus lietojumus kuriem nav beigu laiks. Iegūst visas rezervācijas kuru laiks pārklājas ar ievad datu mēnesi.
        Sakārto datus pa dienām lai lietotāja saskarne varētu viegli attēlot šos datus kā kalendāru un laika līniju ar rezervācijām.  
        AddMessage("test", "debug");
        return view("reservationModule.calendar");
    }

    //Funkcija AVRZ
    public function ViewMyReservationsPage(Request $request)
    {     
        Iegūst visas lietotāja rezervācijas.
    }

    //Funkcija RZIZ
    public function CreateReservation(Request $request)
    {     
        Pārliecinās ka norādītajā laika intervālā inventāram nav rezervāciju. Ja pārbaude veiksmīga tad izveido rezervācijas ierakstu datu bāzē.
    }

    //Funkcija RLIZ
    public function CreateReservationAndUse(Request $request)
    {     
        Vispirms pārliecinās ka no pašreizējā laika līdz norādītajam, izvēlētais inventārs nav rezervēts. 
        Ja pārbaude veiksmīga tad izveido rezervāciju un izsauc lietojuma izveidošanas funkciju.
        Ja lietojuma izveide ir nesekmīga tad izdzēš rezervācijas ierakstu
    }
}
