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
        //admin model

        //Funkcija PVIR.
        Route::post('/izveidot', [AdminController::class, 'CreateUser'])->name("create");  
        Route::get('/izveidot', [AdminController::class, 'CreateUserView']);  
        
        //Funkcija RDIR.
        Route::post('/rediget', [AdminController::class, 'UpdateUser'])->name("edit");
        Route::get('/rediget', [AdminController::class, 'CreateUserView']);  

        //Funkcija DZIR.
        Route::post('/dzest', [AdminController::class, 'DeleteUser'])->name("delete");  //add  error
        
        //Funkcija APIR.
        Route::get('/apskatitVisus', [AdminController::class, 'CreateUserView']); //add error

        //funkcija RKLT izpildīta ar jquery un ajax
        Route::get('/todaysCalendarData', [AdminController::class, 'RecalculateTime']);
    });

    //user 
    Route::post('/startUsing', [VehicleUseController::class, 'StartUsingVehicle'])->name("startUsing");  
    Route::get('/startUsing', [VehicleUseController::class, 'StartUsingVehicleView'])->name("startUsingView"); 
    Route::get('/confirm', [VehicleUseController::class, 'ConfirmVehicleUsage']); 
    Route::post('/stopUsing', [VehicleUseController::class, 'StopUsingVehicle'])->name("stopUsing");
    Route::get('/stopUsing', [VehicleUseController::class, 'StopUsingVehicleView']);
    Route::get('/myDrives', [VehicleUseController::class, 'MyVehicleUses'])->name("myVehicleUses");  
    Route::get('/myActiveDrives', [VehicleUseController::class, 'MyActiveVehicleUses'])->name("myActiveVehicleUses");  

    Route::get('/makeReservation', [ReservationController::class, 'MakeReservation'])->name("makeReservation");
    Route::post('/makeReservationOrStartUsing', [ReservationController::class, 'ReservationOrUse'])->name("makeReservationOrStartUsing");
    
    Route::post('/removeReservation', [ReservationController::class, 'RemoveReservation'])->name("removeReservation");   
    Route::get('/myReservations', [ReservationController::class, 'MyReservations'])->name("myReservations");
    
    Route::get('/vehicleReservationSelection', [ReservationController::class, 'GetReservationSelection'])->name("reservationS");
    
    Route::get('/user', [UserController::class, 'UserView']);
    
    Route::get('/calendarData', [CalendarController::class, 'GetCalendar']);
    Route::get('/todaysCalendarData', [CalendarController::class, 'GetTodaysCalendar']);
    Route::get('/logout', [UserController::class, 'Logout']);

    Route::get('/synchronizeObjects', [ObjectController::class, 'synchronizeObjects']);

    Route::get('/mail', [VehicleUseController::class, 'SendM']);
});

Route::view('/unauthorized', 'main.unauthorized')->name("unauthorized"); 
Route::get('/login', [UserController::class, 'ShowLogin'])->name("login");
Route::post('/login', [UserController::class, 'RecieveLogin']);
Route::view('/', 'main.publicPage')->name("public");