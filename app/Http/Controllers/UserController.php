<?php

namespace App\Http\Controllers;

use App\Models\RememberMeToken;
use App\Models\DeviceInfo;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Http\Requests\LoginData;
use Carbon\Carbon;

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
        if (Auth::attempt($credentials)) {
            if ($remember) {
                $entryToken = Str::random(64);
                Cookie::queue('entryToken', $entryToken, 525600);//525600 minūtes +-= 1 gads
                RememberMeToken::create([
                    'user' => Auth::user()->id,
                    'token' => $entryToken,
                ]);
            }
            try {

                UserLogin::create([
                    'user_id' => Auth::user()->id,
                    'logged_in_at' => Carbon::now(),
                    'remember_me' => $remember,
                ]);

            } catch (Throwable $e) {
            }

            return redirect("sakums");
        }

        AddMessage(Text(100), "k");
        return redirect()->route('login');
    }

    //Funkcijas PTKT lapas atvēršanas funkcijav
    public function ViewLoginPage(Request $request)
    {
        //Ja lietotājs jau ir pieteicies tad aizved viņu uz galveno strops lapu
        if (Auth::check()) {
            return redirect("sakums");
        }

        //Pārbauda vai lietotājam ir derīgs pieteikšanās cepums.
        $entryToken = Cookie::get('entryToken');
        if ($entryToken != null) {
            $token = RememberMeToken::where('token', '=', $entryToken)->select('user')->first();
            if ($token != null) {
                Auth::loginUsingId($token->user);
                return redirect("sakums");
            }
        }

        return view("userModule.login");
    }

    //Funkcija ATKT (Šai funkcijai nav konkrēta lapa tikai post pieprasījums bez datiem) 
    public function Logout(Request $request)
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('entryToken'));
        return redirect("login");
    }

}
