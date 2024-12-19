<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleUse;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StartVehicleUse;

class VehichleUseController extends Controller
{
    //Funkcija LTSK
    public function StartVehicleUse(StartVehicleUse $request)
    { 
        1.	Vispirms pārliecinās ka neviens pašlaik nelieto šo inventāru.
        2.	Pārbauda vai lietotājs ir rezervējis inventāru uz  šo brīdi. Ja jā tad pāriet uz soli 5. Ja nē tad turpina ar soli 3.
        3.	Pārbauda vai kāds cits ir rezervējis šo inventāru uz šo dienu. Ja jā tad  pabrīdina lietotāju.  Ja lietotājs piespiež pogu lietot tik un tā  tad iet pie nākamā soļa citādi pārtrauc darbību.
        4.	Reģistrē kļūdas situāciju – “Lietojums  sākts citas  personas rezervācijas laikā.”  (inventārs, lietotājs, rezervācija, lietojums).
        5.	Ja izvēlētā inventāra lietojuma  veids ir nolasāms, tad uzdod lietotājam jautājumu – “Vai šis nobraukums 382224km atbilst patiesībai? ” (Nobraukumu iegūst no inventāra) Ja lietotājs atbild jā tad iet uz soli  9, ja nē tad iet uz soli 6.
        6.	Uzdod lietotājam jautājumu “Kāds ir pašreizējais nobraukums?”. 
        7.	Reģistrē kļūdas  gadījumu – “Lietojuma nesakritība”(inventārs, lietotājs, nobraukums ko lika apstiprināt, tikko ievadītais nobraukums, lietojums).
        8.	Pārraksta inventāra lietojumu datu bāzē.
        9.	Izveido lietojumu ar tukšu beigu laiku.
    }

    //Funkcija LTBG
    public function FinishVehichleUse(Request $request)
    { 
        1.	Iegūst laika ilgumu starp lietojuma sākumu un pašreizējo brīdi.
        2.	Pārbaida vai kādam citam darbiniekam pašlaik nav rezervācija uz šo inventāru. Ja ir tad piefiksē kļūdas gadījumu – “Darbinieks beidzis lietojumu cita darbinieka rezervācijas laikā” (inventārs, lietotājs, lietojums, rezervācija).
        3.	Ja šī inventāra lietojuma veids ir motor stundas vai nobraukums tad turpina ar soli 3, citādāk turpina ar soli 6.
        4.	Pārbauda vai lietojuma daudzums ir ievadīts.
        5.	Pārbauda vai lietojuma daudzums ir lielāks par datu bāzē inventāra esošo lietojumu.
        6.	Pārbauda vai lietojuma daudzums ir mazāks par:
        motor stundām: datu bāzē esošais lietojumus + 8 + izmantoto stundu skaits
        nobraukumam: datu bāzē esošais lietojumus + (izmantoto stundu skaits * 80)
        7.	Pāriet uz soli  7.
        8.	Rēķinot laiku tiek summēti visi laika intervāli starp sākuma un beigu laiku kas atrodas arī laika intervālā (07:00 – 18:00). (Šī ir tā pati laika rēķināšanas metode kas funkcijā RKLT)
        9.	Saglabā lietojumu ar beigu laiku kas vienāds ar pašreizējo laiku un lietojumu kas vienāds ar ievadīto vai aprēķināto (atkarībā no lietojuma veida).

    }

    //Funkcija LTAP
    public function ViewMyFinishedVehichleUsesPage(Request $request)
    { 
        Iegūst visus šīs personas lietojumus kur beigu laiks ir tukšs.
    }

    //Funkcija ALTA
    public function ViewMyActiveVehichleUsesPage(Request $request)
    { 
        Iegūst visus šī lietotāja lietojumus.
    }
}
