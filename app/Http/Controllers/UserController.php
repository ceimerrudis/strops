<?php

namespace App\Http\Controllers;

use App\Models\RememberMeToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

//Lietotāju modulis
class UserController extends Controller
{
    //Funkcija PTKT 
    //Nodrošina pieteikšanās funkcijas loģiku
    public function RecieveLogin(LoginData $request)
    {
        //ievaddatu iegūšana
        $credentials = $request->only('username', 'password');
        $remember = $request->has('rememberMe');
        
        //Izmanto iebūvēto autorizāciju, bet implementē atceries mani darbību manuāli. 
        if(Auth::attempt($credentials))
        {
            if($remember)
            {
                $entryToken = Str::random(64);
                Cookie::queue('entryToken', $entryToken, 525600);//525600 minūtes +-= 1 gads
                RememberMeToken::create([
                    'user' => Auth::user()->id,
                    'token' => $entryToken,
                ]);
            }
            return redirect(route("reservationS"));
        }
        
        $msg = Text(100);
        return redirect()->route('login')->withInput()->with('msg', $msg);
    }

    //Funkcijas PTKT lapas atvēršanas funkcija
    public function ViewLoginPage(Request $request)
    {     
        //Ja lietotājs jau ir pieteicies tad aizved viņu uz galveno strops lapu
        if(Auth::check())
        {
            return redirect(route("kalendars"));
        }
        
        //Pārbauda vai lietotājam ir derīgs pieteikšanās cepums.
        $entryToken = Cookie::get('entryToken');
        if($entryToken != null)
        {
            $user = RememberMeToken::where('token', '=', $entryToken)->select('user')->first()->user;
            if($user != null)
            {
                Auth::login($user);
                return redirect(route("kalendars"));
            }
        }
        //msg pārbaude jāveic lai paziņotu lietotājam ja kāds no ievadlaukiem nav pareizi aizpildīts.
        $msg = "";
        if($request->has("msg"))
            $msg = $request->input('msg');

        return view("userModule.login", compact('msg'));
    }

    //Funkcija ATKT (Šai funkcijai nav konkrēta lapa tikai post pieprasījums bez datiem) 
    public function Logout()
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('entryToken'));
        return redirect("login");
    }

}
