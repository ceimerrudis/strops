<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MultipleRequestProtectionMiddleware
{
    /*
    Šī pārbaude tiek veikta katram POST pieprasījumam lai nodrošinātu ka 2 vienādi pieprasījumi netiek izpildīti.
    Šī pārbaude paļaujas ka lietotājs ir pieteicies sistēmā
    */
    public function handle(Request $request, Closure $next)
    {
        //Veic šo pārbaudi tikai datu sūtīšanai
        // jo pastāv liela iespēja ka lietotājs tiešām gribēs pieprasīt vienu un to pašu lapu vairākas reizes, 
        // bet ir maza iespēja ka viņš vēlas saglabāt divus vienādus datus dažu sekunžu laikā.
        //Ja nav zināms sūtītājs  tad nevaram pārbaudīt vai šis ir duplikāt pieprasījums.
        if ($request->isMethod('get') || Auth::user() == null)
            return $next($request);
    
        $userId = Auth::user()->id;
        //Lai neglabātu visu pieprasījumu izmanto jaucējfunkciju
        $hash = md5($userId . '|' . $request->fullUrl() . '|' . json_encode($request->all()));
        
        if (Cache::has($hash)) {
            return redirect("dubultPieprasijums");
        }
        //Saglabā šo pieprasījumu 10 sekundes
        Cache::put($hash, true, $seconds = 4);

        return $next($request);
    }
}
