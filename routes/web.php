<?php

use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\IsAdminMiddleware;

use App\Http\Controllers\VehicleUseController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthenticationMiddleware::class)->group(function () {
    Route::middleware(IsAdminMiddleware::class)->group(function () {
        //Administratoru modulis
        //Funkcija PVIR.
        Route::get('/izveidot', [AdminController::class, 'ViewCreateEntryPage'])->name("viewcreate");  
        Route::post('/izveidot', [AdminController::class, 'CreateEntry'])->name("create");  
        //Funkcija RDIR.
        Route::get('/rediget', [AdminController::class, 'ViewUpdateEntryPage'])->name("viewEdit"); 
        Route::post('/rediget', [AdminController::class, 'UpdateEntry'])->name("edit");
        //Funkcija DZIR.
        Route::post('/dzest', [AdminController::class, 'DeleteEntry'])->name("delete");  //add  error
        //Funkcija APIR.
        Route::get('/apskatitVisus', [AdminController::class, 'ViewAllEntriesPage'])->name("viewAllEntries"); //add error
        //funkcija RKLT izpildīta ar jquery un ajax
        Route::get('/parrekinatlaiku', [AdminController::class, 'RecalculateTime']);
    });

    //Objektu modulis
    //Funkcija ATJO
    Route::get('/atjaunotObjektus', [ObjectController::class, 'UpdateObjects']);
    //Funkcija PVAT
    Route::get('/pievienotAtskaiti', [ObjectController::class, 'ViewCreateReportPage']);
    Route::post('/pievienotAtskaiti', [ObjectController::class, 'CreateReport']);
    //Funkcija RDAT
    Route::get('/redigetAtskaiti', [ObjectController::class, 'ViewUpdateReportPage']);
    Route::post('/redigetAtskaiti', [ObjectController::class, 'UpdateReport']);
    //Funkcija SVAT
    Route::get('/apskatitatskaites', [ObjectController::class, 'ViewReports']);
    
    //Rezervāciju modulis
    //Funkcija AVRZ
    Route::get('/manasRezervacijas', [ReservationController::class, 'ViewMyReservationsPage']);
    //Funkcija RZKL
    Route::get('/sakums', [ReservationController::class, 'ViewCalendarPage'])->name("calendar");
    //Šo lapu izsauc ar ajax (tas atļauj vienā reizē ielādēt tikai viena mēneša rezervācijas)
    Route::get('/kalendars', [ReservationController::class, 'GetCalendarData'])->name("start");
    //Funkcija RZIZ
    Route::post('/izveidotRezervaciju', [ReservationController::class, 'CreateReservation'])->name("createReservation");
    //Funkcija RLIZ
    Route::post('/izveidotRezervacijuUnLietojumu', [ReservationController::class, 'CreateReservationAndUse'])->name("createReservationAndUse");

    //Lietojumu modulis
    //Funkcija LTSK
    Route::get('/saktLietojumu', [VehicleUseController::class, 'ViewStartVehicleUsePage'])->name("startUse");
    Route::post('/saktLietojumu', [VehicleUseController::class, 'StartVehicleUse']);
    //Funkcija LTBG
    Route::get('/beigtLietojumu', [VehicleUseController::class, 'ViewFinishVehicleUsePage']);
    Route::post('/beigtLietojumu', [VehicleUseController::class, 'FinishVehicleUse']);
    //Funkcija LTAP
    Route::get('/maniPabeigtieLietojumi', [VehicleUseController::class, 'ViewMyFinishedVehicleUsesPage']);
    //Funkcija ALTA
    Route::get('/maniNepabeigtieLietojumi', [VehicleUseController::class, 'ViewMyActiveVehicleUsesPage']);

    //Lietotāju modulis
    //Funkcija ATKT
    Route::get('/logout', function () { return redirect('/atteikties'); });
    Route::get('/atteikties', [UserController::class, 'Logout']);
});
//Funkcijas PTKT ceļi
//Pievienošanās ceļš nosaukts arī angliski lai vieglāk piekļūt lapai
Route::get('/login', function () { return redirect('/pieteikties'); });
Route::get('/pieteikties', [UserController::class, 'ViewLoginPage'])->name("login");
Route::post('/pieteikties', [UserController::class, 'RecieveLogin']);


//Papildus lapas kas neitilpst nevienā moduilī 
//Publiskās lapas ceļš
Route::view('/', 'publicPage')->name("public"); 

//Šī ir sistēmas kļūdas lapa ko redz pēc tam kad veikts pieprasījums bez nepieciešamajām tiesībām. 
Route::view('/unauthorized', 'unauthorized')->name("unauthorized");
Route::view('/kluda', 'kluda')->name("kluda");